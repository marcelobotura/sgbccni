<?php
// Caminho: frontend/usuario/livros_emprestados.php

define('BASE_PATH', dirname(__DIR__, 2));
require_once BASE_PATH . '/backend/config/config.php';
require_once BASE_PATH . '/backend/includes/session.php';

if (!isset($_SESSION['usuario_id'])) {
    $_SESSION['erro'] = "Você precisa estar logado.";
    header("Location: " . URL_BASE . "frontend/login/login.php");
    exit;
}

$usuario_id = $_SESSION['usuario_id'];

// 🔎 Livros atualmente emprestados (únicos)
$stmt = $pdo->query("
    SELECT DISTINCT l.id, l.titulo, l.autor, l.capa_local, l.capa_url
    FROM livros l
    JOIN emprestimos e ON l.id = e.livro_id
    WHERE e.status = 'emprestado'
    ORDER BY l.titulo ASC
");
$livros = $stmt->fetchAll();

function capaLivro(array $livro): string {
    if (!empty($livro['capa_local']) && file_exists(BASE_PATH . '/storage/uploads/capas/' . $livro['capa_local'])) {
        return URL_BASE . 'storage/uploads/capas/' . $livro['capa_local'];
    }
    return $livro['capa_url'] ?? (URL_BASE . 'frontend/assets/img/livro_padrao.png');
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <title>Livros Emprestados - <?= NOME_SISTEMA ?></title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <style>
    .capa {
      width: 100%;
      height: 160px;
      object-fit: cover;
    }
    .card-body {
      padding: 0.75rem;
    }
    .card-title {
      font-size: 0.95rem;
      margin-bottom: 0.25rem;
    }
    .card-text {
      font-size: 0.85rem;
      margin-bottom: 0.5rem;
    }
    .btn-sm-custom {
      font-size: 0.8rem;
      padding: 0.3rem 0.5rem;
    }
  </style>
</head>
<body>
<div class="container py-4">
  <h2 class="mb-4"><i class="bi bi-journal-x"></i> Livros Emprestados (Reserváveis)</h2>

  <?php if (empty($livros)): ?>
    <div class="alert alert-info">Nenhum livro emprestado no momento.</div>
  <?php else: ?>
    <div class="row row-cols-2 row-cols-sm-3 row-cols-md-4 g-3">
      <?php foreach ($livros as $livro): ?>
        <div class="col">
          <div class="card h-100 shadow-sm border-0">
            <img src="<?= capaLivro($livro) ?>" class="card-img-top capa" alt="Capa do livro">
            <div class="card-body text-center">
              <h6 class="card-title"><?= htmlspecialchars($livro['titulo']) ?></h6>
              <p class="card-text text-muted mb-2"><?= htmlspecialchars($livro['autor']) ?></p>
              <a href="solicitar_emprestimo.php?id=<?= $livro['id'] ?>" class="btn btn-outline-primary btn-sm w-100 btn-sm-custom">
                <i class="bi bi-bookmark-plus"></i> Reservar
              </a>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>

  <a href="index.php" class="btn btn-secondary btn-sm mt-4">
    <i class="bi bi-arrow-left"></i> Voltar ao Catálogo
  </a>
</div>
</body>
</html>
