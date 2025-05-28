<?php
// Correção: BASE_PATH deve apontar para o diretório 'backend'
define('BASE_PATH', __DIR__ . '/../backend');
require_once BASE_PATH . '/config/config.php';
require_once BASE_PATH . '/includes/session.php';
include_once BASE_PATH . '/includes/header.php';

// O parâmetro de busca agora pode ser 'codigo' (para o QR Code) ou 'isbn' (para links diretos)
$codigo_interno = $_GET['codigo'] ?? '';
$isbn_param = $_GET['isbn'] ?? '';

$where_clause = '';
$bind_type = '';
$bind_value = '';

if (!empty($codigo_interno)) {
    $where_clause = "codigo_interno = ?";
    $bind_type = "s";
    $bind_value = $codigo_interno;
} elseif (!empty($isbn_param)) {
    $where_clause = "isbn = ?";
    $bind_type = "s";
    $bind_value = $isbn_param;
} else {
    echo "<div class='container py-5'><div class='alert alert-danger'>Código Interno ou ISBN não informado.</div></div>";
    include_once BASE_PATH . '/includes/footer.php';
    exit;
}

$stmt = $conn->prepare("SELECT
                            l.*,
                            a.nome AS autor_nome,
                            e.nome AS editora_nome,
                            c.nome AS categoria_nome
                        FROM livros l
                        LEFT JOIN autores a ON l.autor_id = a.id
                        LEFT JOIN editoras e ON l.editora_id = e.id
                        LEFT JOIN categorias c ON l.categoria_id = c.id
                        WHERE " . $where_clause);

if (!$stmt) {
    echo "<div class='container py-5'><div class='alert alert-danger'>Erro ao preparar a consulta do livro: " . $conn->error . "</div></div>";
    include_once BASE_PATH . '/includes/footer.php';
    exit;
}

$stmt->bind_param($bind_type, $bind_value);
$stmt->execute();
$result = $stmt->get_result();
$livro = $result->fetch_assoc();
$stmt->close();

if (!$livro) {
    echo "<div class='container py-5'><div class='alert alert-warning'>Livro não encontrado.</div></div>";
    include_once BASE_PATH . '/includes/footer.php';
    exit;
}

$livro_id = $livro['id'];
$usuario_id = $_SESSION['usuario_id'] ?? null;

// --- Processamento de Ações POST (Lido/Favorito) ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $usuario_id) {
    if (isset($_POST['acao'])) {
        $stmt_acao = null;
        if ($_POST['acao'] === 'lido') {
            $stmt_acao = $conn->prepare("INSERT INTO livros_usuarios (usuario_id, livro_id, lido)
                                        VALUES (?, ?, 1)
                                        ON DUPLICATE KEY UPDATE lido = 1");
        } elseif ($_POST['acao'] === 'favorito') {
            $stmt_acao = $conn->prepare("INSERT INTO livros_usuarios (usuario_id, livro_id, favorito)
                                        VALUES (?, ?, 1)
                                        ON DUPLICATE KEY UPDATE favorito = 1");
        }

        if ($stmt_acao) {
            $stmt_acao->bind_param("ii", $usuario_id, $livro_id);
            if ($stmt_acao->execute()) {
                $_SESSION['sucesso'] = "✅ Ação registrada com sucesso!";
            } else {
                $_SESSION['erro'] = "❌ Erro ao registrar a ação: " . $stmt_acao->error;
            }
            $stmt_acao->close();
        } else {
            $_SESSION['erro'] = "Ação inválida.";
        }
    } else {
        $_SESSION['erro'] = "Nenhuma ação especificada.";
    }
    header("Location: livro.php?isbn=" . urlencode($livro['isbn']));
    exit;
}

$rel_res = null;
$relacionados = $conn->prepare("SELECT id, titulo, capa_local, isbn FROM livros
                                WHERE id != ?
                                AND (autor_id = ? OR editora_id = ?) LIMIT 4");

if ($relacionados) {
    $relacionados->bind_param("iii", $livro_id, $livro['autor_id'], $livro['editora_id']);
    $relacionados->execute();
    $rel_res = $relacionados->get_result();
    $relacionados->close();
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($livro['titulo']) ?> - Biblioteca</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="<?= URL_BASE ?>assets/css/estilo-base.css">
</head>
<body>
<div class="container py-5">
  <?php if (isset($_SESSION['sucesso'])): ?>
    <div class="alert alert-success"><?= htmlspecialchars($_SESSION['sucesso']); unset($_SESSION['sucesso']); ?></div>
  <?php endif; ?>
  <?php if (isset($_SESSION['erro'])): ?>
    <div class="alert alert-danger"><?= htmlspecialchars($_SESSION['erro']); unset($_SESSION['erro']); ?></div>
  <?php endif; ?>

  <div class="row">
    <div class="col-md-4">
      <?php if (!empty($livro['capa_local'])): ?>
        <img src="<?= htmlspecialchars(URL_BASE . $livro['capa_local']) ?>" alt="Capa" class="img-fluid rounded shadow">
      <?php else: ?>
        <div class="bg-light text-muted text-center p-5 rounded" style="height: 300px; display: flex; align-items: center; justify-content: center;">Sem capa disponível</div>
      <?php endif; ?>
    </div>
    <div class="col-md-8">
      <h2><?= htmlspecialchars($livro['titulo']) ?></h2>
      <p><strong>Autor(es):</strong> <?= htmlspecialchars($livro['autor_nome'] ?? 'N/A') ?></p>
      <p><strong>Editora:</strong> <?= htmlspecialchars($livro['editora_nome'] ?? 'N/A') ?></p>
      <p><strong>Categoria:</strong> <?= htmlspecialchars($livro['categoria_nome'] ?? 'N/A') ?></p>
      <?php if (!empty($livro['volume'])): ?><p><strong>Volume:</strong> <?= htmlspecialchars($livro['volume']) ?></p><?php endif; ?>
      <?php if (!empty($livro['edicao'])): ?><p><strong>Edição:</strong> <?= htmlspecialchars($livro['edicao']) ?></p><?php endif; ?>
      <p><strong>ISBN:</strong> <?= htmlspecialchars($livro['isbn']) ?></p>
      <p><strong>Código Interno:</strong> <?= htmlspecialchars($livro['codigo_interno']) ?></p>
      <p><strong>Descrição:</strong><br><?= nl2br(htmlspecialchars($livro['descricao'])) ?></p>

      <?php if (!empty($livro['link_digital'])): ?>
        <p><a href="<?= htmlspecialchars($livro['link_digital']) ?>" target="_blank" class="btn btn-info">🔗 Acessar Livro Digital</a></p>
      <?php endif; ?>

      <form method="POST" class="mt-3 d-flex gap-2">
        <?php if ($usuario_id): ?>
            <button type="submit" name="acao" value="lido" class="btn btn-outline-success">📖 Marcar como Lido</button>
            <button type="submit" name="acao" value="favorito" class="btn btn-outline-warning">⭐ Adicionar aos Favoritos</button>
        <?php else: ?>
            <p class="text-muted">Faça login para marcar como lido ou favorito.</p>
        <?php endif; ?>
        <a href="exportar_pdf_livro.php?isbn=<?= urlencode($livro['isbn']) ?>" class="btn btn-outline-danger">📄 Exportar PDF</a>
        <?php if (!empty($livro['qr_code'])): ?>
            <img src="<?= htmlspecialchars(URL_BASE . $livro['qr_code']) ?>" alt="QR Code" style="width: 150px; height: 150px; margin-top: 10px;">
        <?php endif; ?>
      </form>
    </div>
  </div>

  <?php if (isset($rel_res) && $rel_res->num_rows > 0): ?>
    <hr>
    <h4>📚 Livros Relacionados</h4>
    <div class="row row-cols-1 row-cols-md-4 g-4 mt-2">
      <?php while($r = $rel_res->fetch_assoc()): ?>
        <div class="col">
          <div class="card h-100 shadow-sm">
            <?php if (!empty($r['capa_local'])): ?>
              <img src="<?= htmlspecialchars(URL_BASE . $r['capa_local']) ?>" class="card-img-top" style="height: 200px; object-fit: cover;" alt="Capa Livro Relacionado">
            <?php else: ?>
              <div class="bg-light text-muted text-center p-5 rounded-top" style="height: 200px; display: flex; align-items: center; justify-content: center;">Sem capa</div>
            <?php endif; ?>
            <div class="card-body">
              <h6 class="card-title text-truncate"><?= htmlspecialchars($r['titulo']) ?></h6>
              <a href="livro.php?isbn=<?= urlencode($r['isbn']) ?>" class="btn btn-sm btn-primary">Ver Detalhes</a>
            </div>
          </div>
        </div>
      <?php endwhile; ?>
    </div>
  <?php endif; ?>
</div>

<?php include_once BASE_PATH . '/includes/footer.php'; ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="<?= URL_BASE ?>assets/js/tema.js"></script>
</body>
</html>
