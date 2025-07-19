<?php
// Caminho: frontend/usuario/livro.php

define('BASE_PATH', realpath(__DIR__ . '/../../backend'));
require_once BASE_PATH . '/config/config.php';
require_once BASE_PATH . '/includes/session.php';
require_once BASE_PATH . '/includes/header.php';
require_once __DIR__ . '/protect_usuario.php';

exigir_login('usuario');

$id = intval($_GET['id'] ?? 0);
$codigo = $_GET['codigo'] ?? '';
$isbn = $_GET['isbn'] ?? '';

$where = '';
$param = null;

if ($id > 0) {
    $where = 'id = ?';
    $param = $id;
} elseif (!empty($codigo)) {
    $where = 'codigo_interno = ?';
    $param = $codigo;
} elseif (!empty($isbn)) {
    $where = 'isbn = ?';
    $param = $isbn;
} else {
    echo "<div class='container py-5'><div class='alert alert-danger'>Código, ID ou ISBN não informado.</div></div>";
    include_once BASE_PATH . '/includes/footer.php';
    exit;
}

$stmt = $pdo->prepare("SELECT * FROM livros WHERE $where");
$stmt->execute([$param]);
$livro = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$livro) {
    echo "<div class='container py-5'><div class='alert alert-warning'>Livro não encontrado.</div></div>";
    include_once BASE_PATH . '/includes/footer.php';
    exit;
}

function buscar_nome_tag($id, $pdo) {
    if (!$id) return 'N/A';
    $stmt = $pdo->prepare("SELECT nome FROM tags WHERE id = ?");
    $stmt->execute([$id]);
    $res = $stmt->fetch();
    return $res ? $res['nome'] : 'N/A';
}

$autor = buscar_nome_tag($livro['autor_id'] ?? null, $pdo);
$editora = buscar_nome_tag($livro['editora_id'] ?? null, $pdo);
$categoria = buscar_nome_tag($livro['categoria_id'] ?? null, $pdo);

$livro_id = $livro['id'];
$usuario_id = $_SESSION['usuario_id'] ?? null;

if ($usuario_id) {
    $ip = $_SERVER['REMOTE_ADDR'] ?? null;
    $stmtLog = $pdo->prepare("INSERT INTO log_visualizacoes (usuario_id, livro_id, ip) VALUES (?, ?, ?)");
    $stmtLog->execute([$usuario_id, $livro_id, $ip]);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $usuario_id) {
    if (in_array($_POST['acao'], ['lido', 'favorito', 'remover_favorito'])) {
        if ($_POST['acao'] === 'remover_favorito') {
            $sql = "UPDATE livros_usuarios SET favorito = 0 WHERE usuario_id = ? AND livro_id = ?";
            $pdo->prepare($sql)->execute([$usuario_id, $livro_id]);
            $_SESSION['sucesso'] = "⭐ Removido dos favoritos.";
        } else {
            $campo = $_POST['acao'];
            $sql = "INSERT INTO livros_usuarios (usuario_id, livro_id, $campo) VALUES (?, ?, 1)
                    ON DUPLICATE KEY UPDATE $campo = 1";
            $pdo->prepare($sql)->execute([$usuario_id, $livro_id]);
            $_SESSION['sucesso'] = "✅ Livro marcado como $campo!";
        }
        header("Location: livro.php?id=$livro_id");
        exit;
    }

    if ($_POST['acao'] === 'comentar' && !empty($_POST['comentario'])) {
        $comentario = trim($_POST['comentario']);
        $stmt = $pdo->prepare("INSERT INTO comentarios (usuario_id, livro_id, texto, criado_em) VALUES (?, ?, ?, NOW())");
        $stmt->execute([$usuario_id, $livro_id, $comentario]);
        $_SESSION['sucesso'] = "Comentário enviado!";
        header("Location: livro.php?id=$livro_id");
        exit;
    }
}

$stmt_rel = $pdo->prepare("SELECT id, titulo, capa_local, capa_url FROM livros
                          WHERE id != ? AND (autor_id = ? OR editora_id = ?) LIMIT 4");
$stmt_rel->execute([$livro_id, $livro['autor_id'], $livro['editora_id']]);
$relacionados = $stmt_rel->fetchAll(PDO::FETCH_ASSOC);

$stmt_com = $pdo->prepare("SELECT c.texto, c.criado_em, u.nome FROM comentarios c
                           JOIN usuarios u ON c.usuario_id = u.id
                           WHERE c.livro_id = ? ORDER BY c.criado_em DESC");
$stmt_com->execute([$livro_id]);
$comentarios = $stmt_com->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <title><?= htmlspecialchars($livro['titulo']) ?> - Biblioteca CNI</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <link rel="stylesheet" href="<?= URL_BASE ?>frontend/assets/css/base.css">
</head>
<body>

<main class="container py-4">
  <header class="mb-4 d-flex justify-content-between align-items-center">
    <h2><i class="bi bi-book"></i> Detalhes do Livro</h2>
    <a href="javascript:history.back()" class="btn btn-outline-secondary">← Voltar</a>
  </header>

  <?php if (isset($_SESSION['sucesso'])): ?>
    <div class="alert alert-success"><?= htmlspecialchars($_SESSION['sucesso']); unset($_SESSION['sucesso']); ?></div>
  <?php endif; ?>

  <div class="row g-4">
    <div class="col-md-4 text-center">
      <?php
        $capa = (!empty($livro['capa']) && file_exists(BASE_PATH . '/../uploads/capas/' . $livro['capa']))
          ? URL_BASE . 'uploads/capas/' . $livro['capa']
          : (!empty($livro['capa_url']) ? $livro['capa_url'] : URL_BASE . 'assets/img/sem_capa.png');
      ?>
      <img src="<?= $capa ?>" class="img-fluid rounded shadow mb-3" alt="Capa" style="max-height: 350px;">
      <?php if (!empty($livro['qr_code'])): ?>
        <div><img src="<?= URL_BASE . $livro['qr_code'] ?>" alt="QR Code" style="width: 120px;"></div>
      <?php endif; ?>
    </div>

    <div class="col-md-8">
      <h3><?= htmlspecialchars($livro['titulo']) ?></h3>
      <p><strong>Autor:</strong> <?= $autor ?></p>
      <p><strong>Editora:</strong> <?= $editora ?></p>
      <p><strong>Categoria:</strong> <?= $categoria ?></p>
      <p><strong>Volume:</strong> <?= htmlspecialchars($livro['volume']) ?></p>
      <p><strong>Edição:</strong> <?= htmlspecialchars($livro['edicao']) ?></p>
      <p><strong>ISBN:</strong> <?= htmlspecialchars($livro['isbn']) ?></p>
      <p><strong>Código Interno:</strong> <?= htmlspecialchars($livro['codigo_interno']) ?></p>
      <p><strong>Descrição:</strong><br><?= nl2br(htmlspecialchars($livro['descricao'])) ?></p>

      <?php if (!empty($livro['link_digital'])): ?>
        <a href="<?= htmlspecialchars($livro['link_digital']) ?>" class="btn btn-info mt-2" target="_blank">
          <i class="bi bi-book"></i> Acessar Livro Digital
        </a>
      <?php endif; ?>

      <form method="POST" class="mt-3 d-flex gap-2 flex-wrap">
        <button type="submit" name="acao" value="lido" class="btn btn-outline-success"><i class="bi bi-check2-square"></i> Marcar como Lido</button>
        <button type="submit" name="acao" value="favorito" class="btn btn-outline-warning"><i class="bi bi-star"></i> Favoritar</button>
        <button type="submit" name="acao" value="remover_favorito" class="btn btn-outline-danger"><i class="bi bi-star-fill"></i> Remover Favorito</button>
        <a href="exportar_pdf_livro.php?isbn=<?= urlencode($livro['isbn']) ?>" class="btn btn-outline-dark">
          <i class="bi bi-file-earmark-pdf"></i> Exportar PDF
        </a>
      </form>
    </div>
  </div>

  <?php if ($relacionados): ?>
    <hr>
    <h4 class="mt-4">📚 Livros Relacionados</h4>
    <div class="row row-cols-1 row-cols-sm-2 row-cols-md-4 g-3 mt-2">
      <?php foreach ($relacionados as $r): ?>
        <?php
          $capa_rel = (!empty($r['capa']) && file_exists(BASE_PATH . '/../uploads/capas/' . $r['capa']))
            ? URL_BASE . 'uploads/capas/' . $r['capa']
            : (!empty($r['capa_url']) ? $r['capa_url'] : URL_BASE . 'assets/img/sem_capa.png');
        ?>
        <div class="col">
          <div class="card h-100 shadow-sm">
            <img src="<?= $capa_rel ?>" class="card-img-top" alt="Capa" style="height: 220px; object-fit: cover;">
            <div class="card-body text-center">
              <h6 class="card-title text-truncate"><?= htmlspecialchars($r['titulo']) ?></h6>
              <a href="livro.php?id=<?= $r['id'] ?>" class="btn btn-sm btn-outline-primary">Ver Detalhes</a>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>

  <!-- 💬 Comentários -->
  <hr>
  <h4 class="mt-4">💬 Comentários</h4>

  <form method="POST" class="mb-4">
    <input type="hidden" name="acao" value="comentar">
    <textarea name="comentario" class="form-control mb-2" rows="3" placeholder="Escreva seu comentário..." required></textarea>
    <button type="submit" class="btn btn-primary"><i class="bi bi-send"></i> Enviar Comentário</button>
  </form>

  <?php if ($comentarios): ?>
    <?php foreach ($comentarios as $c): ?>
      <div class="border rounded p-3 mb-3 bg-light">
        <strong><?= htmlspecialchars($c['nome']) ?></strong>
        <small class="text-muted"><?= date('d/m/Y H:i', strtotime($c['criado_em'])) ?></small>
        <p class="mb-0"><?= nl2br(htmlspecialchars($c['texto'])) ?></p>
      </div>
    <?php endforeach; ?>
  <?php else: ?>
    <p class="text-muted">Nenhum comentário ainda.</p>
  <?php endif; ?>
</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
