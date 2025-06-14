<?php
// 🔧 Base e includes
define('BASE_PATH', realpath(__DIR__ . '/../../backend'));
require_once BASE_PATH . '/config/config.php';
require_once BASE_PATH . '/includes/session.php';
include_once BASE_PATH . '/includes/header.php';

// 📥 Parâmetros GET
$codigo_interno = $_GET['codigo'] ?? '';
$isbn_param = $_GET['isbn'] ?? '';

$where_clause = '';
$param_value = '';

if (!empty($codigo_interno)) {
    $where_clause = "codigo_interno = ?";
    $param_value = $codigo_interno;
} elseif (!empty($isbn_param)) {
    $where_clause = "isbn = ?";
    $param_value = $isbn_param;
} else {
    echo "<div class='container py-5'><div class='alert alert-danger'>Código Interno ou ISBN não informado.</div></div>";
    include_once BASE_PATH . '/includes/footer.php';
    exit;
}

// 🔍 Consulta do livro
try {
    $stmt = $conn->prepare("SELECT * FROM livros WHERE $where_clause");
    $stmt->execute([$param_value]);
    $livro = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$livro) {
        echo "<div class='container py-5'><div class='alert alert-warning'>Livro não encontrado.</div></div>";
        include_once BASE_PATH . '/includes/footer.php';
        exit;
    }
} catch (PDOException $e) {
    echo "<div class='container py-5'><div class='alert alert-danger'>Erro ao buscar livro: {$e->getMessage()}</div></div>";
    include_once BASE_PATH . '/includes/footer.php';
    exit;
}

// 🔗 Dados usuário
$livro_id = $livro['id'];
$usuario_id = $_SESSION['usuario_id'] ?? null;

// 🎯 Processamento de ações POST (lido ou favorito)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $usuario_id) {
    if (isset($_POST['acao'])) {
        try {
            if ($_POST['acao'] === 'lido') {
                $sql = "INSERT INTO livros_usuarios (usuario_id, livro_id, lido)
                        VALUES (?, ?, 1)
                        ON DUPLICATE KEY UPDATE lido = 1";
            } elseif ($_POST['acao'] === 'favorito') {
                $sql = "INSERT INTO livros_usuarios (usuario_id, livro_id, favorito)
                        VALUES (?, ?, 1)
                        ON DUPLICATE KEY UPDATE favorito = 1";
            } else {
                throw new Exception("Ação inválida.");
            }

            $stmt = $conn->prepare($sql);
            $stmt->execute([$usuario_id, $livro_id]);

            $_SESSION['sucesso'] = "✅ Ação registrada com sucesso!";
        } catch (Exception $e) {
            $_SESSION['erro'] = "❌ Erro: " . $e->getMessage();
        }

        header("Location: livro.php?isbn=" . urlencode($livro['isbn']));
        exit;
    }
}

// 🔗 Livros relacionados
$relacionados = [];
try {
    $stmt_rel = $conn->prepare("SELECT id, titulo, capa_local, isbn FROM livros
                                WHERE id != ? AND (autor_id = ? OR editora_id = ?)
                                LIMIT 4");
    $stmt_rel->execute([$livro_id, $livro['autor_id'], $livro['editora_id']]);
    $relacionados = $stmt_rel->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    error_log("Erro ao buscar relacionados: " . $e->getMessage());
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
            <p><strong>Autor:</strong> <?= htmlspecialchars($livro['autor_id'] ?? 'N/A') ?></p>
            <p><strong>Editora:</strong> <?= htmlspecialchars($livro['editora_id'] ?? 'N/A') ?></p>
            <p><strong>Categoria:</strong> <?= htmlspecialchars($livro['categoria_id'] ?? 'N/A') ?></p>
            <?php if (!empty($livro['volume'])): ?><p><strong>Volume:</strong> <?= htmlspecialchars($livro['volume']) ?></p><?php endif; ?>
            <?php if (!empty($livro['edicao'])): ?><p><strong>Edição:</strong> <?= htmlspecialchars($livro['edicao']) ?></p><?php endif; ?>
            <p><strong>ISBN:</strong> <?= htmlspecialchars($livro['isbn']) ?></p>
            <p><strong>Código Interno:</strong> <?= htmlspecialchars($livro['codigo_interno']) ?></p>
            <p><strong>Descrição:</strong><br><?= nl2br(htmlspecialchars($livro['descricao'])) ?></p>

            <?php if (!empty($livro['link_digital'])): ?>
                <p><a href="<?= htmlspecialchars($livro['link_digital']) ?>" target="_blank" class="btn btn-info">🔗 Acessar Livro Digital</a></p>
            <?php endif; ?>

            <form method="POST" class="mt-3 d-flex gap-2 flex-wrap">
                <?php if ($usuario_id): ?>
                    <button type="submit" name="acao" value="lido" class="btn btn-outline-success">📖 Marcar como Lido</button>
                    <button type="submit" name="acao" value="favorito" class="btn btn-outline-warning">⭐ Adicionar aos Favoritos</button>
                <?php else: ?>
                    <p class="text-muted">Faça login para marcar como lido ou favorito.</p>
                <?php endif; ?>
                <a href="exportar_pdf_livro.php?isbn=<?= urlencode($livro['isbn']) ?>" class="btn btn-outline-danger">📄 Exportar PDF</a>
                <?php if (!empty($livro['qr_code'])): ?>
                    <img src="<?= htmlspecialchars(URL_BASE . $livro['qr_code']) ?>" alt="QR Code" style="width: 150px; height: 150px;">
                <?php endif; ?>
            </form>
        </div>
    </div>

    <?php if (!empty($relacionados)): ?>
        <hr>
        <h4>📚 Livros Relacionados</h4>
        <div class="row row-cols-1 row-cols-md-4 g-4 mt-2">
            <?php foreach ($relacionados as $r): ?>
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
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<?php include_once BASE_PATH . '/includes/footer.php'; ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="<?= URL_BASE ?>assets/js/tema.js"></script>
</body>
</html>
