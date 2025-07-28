<?php 
// Caminho: public_html/ver_livro.php
declare(strict_types=1);

define('BASE_PATH', dirname(__DIR__));
require_once BASE_PATH . '/backend/config/config.php';
require_once BASE_PATH . '/backend/includes/db.php';
require_once BASE_PATH . '/backend/includes/session.php';

$id = (int)($_GET['id'] ?? 0);
if ($id <= 0) {
  die('Livro inválido.');
}

// Consulta ao banco
$stmt = $pdo->prepare("SELECT l.*, 
  taut.nome AS autor_nome,
  tedit.nome AS editora_nome,
  tcat.nome AS categoria_nome
  FROM livros l
  LEFT JOIN tags taut ON l.autor_id = taut.id
  LEFT JOIN tags tedit ON l.editora_id = tedit.id
  LEFT JOIN tags tcat ON l.categoria_id = tcat.id
  WHERE l.id = ?");
$stmt->execute([$id]);
$livro = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$livro) {
  die('Livro não encontrado.');
}

// 🔍 Verifica se está emprestado atualmente
$stmtStatus = $pdo->prepare("SELECT COUNT(*) FROM emprestimos WHERE livro_id = ? AND status = 'emprestado'");
$stmtStatus->execute([$livro['id']]);
$livro_emprestado = $stmtStatus->fetchColumn() > 0;

// Função da capa
function capaLivro(array $livro): string {
  if (!empty($livro['capa_local']) && file_exists(BASE_PATH . '/storage/uploads/capas/' . $livro['capa_local'])) {
    return URL_BASE . 'storage/uploads/capas/' . $livro['capa_local'];
  }
  return $livro['capa_url'] ?? (URL_BASE . 'frontend/assets/img/livro_padrao.png');
}

$link_preview = $livro['link_leitura'] ?? '#';
$logado = isset($_SESSION['usuario_id']);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <title><?= htmlspecialchars($livro['titulo']) ?> - Detalhes do Livro</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
  <style>
    body { background: #f9f9f9; }
    .capa-livro { max-height: 420px; object-fit: contain; }
    .estrelinhas i { color: gold; }
    .badge { font-size: 0.85em; margin-right: 4px; }
  </style>
</head>
<body>
<div class="container py-5">

  <a href="index.php" class="btn btn-outline-secondary mb-3">
    <i class="bi bi-arrow-left"></i> Voltar
  </a>

  <div class="row g-4">
    <div class="col-md-4 text-center">
      <img src="<?= capaLivro($livro) ?>" alt="Capa do livro" class="img-fluid capa-livro rounded shadow">

      <div class="mt-3">
        <?php if ($logado): ?>
          <?php if ($livro_emprestado): ?>
            <a href="frontend/usuario/reservar_livro.php?id=<?= $livro['id'] ?>" class="btn btn-warning w-100 mb-2">
              <i class="bi bi-calendar-plus"></i> Reservar Livro
            </a>
          <?php else: ?>
            <a href="<?= URL_BASE ?>frontend/usuario/emprestar_livro.php?id=<?= $livro['id'] ?>" class="btn btn-success w-100 mb-2">
              <i class="bi bi-journal-arrow-down"></i> Emprestar Livro
            </a>
          <?php endif; ?>
        <?php else: ?>
          <a href="login.php?redirect=../usuario/emprestar_livro.php&id=<?= $livro['id'] ?>" class="btn btn-primary w-100 mb-2">
            <i class="bi bi-person-lock"></i> Reservar
          </a>
        <?php endif; ?>

        <?php if ($livro['tipo'] === 'digital' && $link_preview !== '#'): ?>
          <a href="<?= htmlspecialchars($link_preview) ?>" target="_blank" class="btn btn-outline-secondary w-100">
            <i class="bi bi-eye"></i> Pré-visualizar
          </a>
        <?php endif; ?>
      </div>
    </div>

    <div class="col-md-8">
      <h2><?= htmlspecialchars($livro['titulo']) ?></h2>
      <p class="text-muted">por <strong><?= htmlspecialchars($livro['autor_nome'] ?? 'Autor desconhecido') ?></strong></p>

      <div class="mb-2 estrelinhas">
        <i class="bi bi-star-fill"></i>
        <i class="bi bi-star-fill"></i>
        <i class="bi bi-star-fill"></i>
        <i class="bi bi-star-half"></i>
        <i class="bi bi-star"></i>
        <small class="ms-2 text-muted">Avaliação média (simulada)</small>
      </div>

      <p><?= nl2br(htmlspecialchars($livro['descricao'] ?? 'Sem sinopse.')) ?></p>

      <hr>
      <div class="row">
        <div class="col-md-6">
          <p><strong>Editora:</strong> <?= htmlspecialchars($livro['editora_nome'] ?? '-') ?></p>
          <p><strong>Ano:</strong> <?= !empty($livro['ano']) ? (int)$livro['ano'] : '-' ?></p>
          <p><strong>Edição:</strong> <?= !empty($livro['edicao']) ? htmlspecialchars((string)$livro['edicao']) : '-' ?></p>
        </div>
        <div class="col-md-6">
          <p><strong>Tipo:</strong> <?= htmlspecialchars($livro['tipo'] ?? '-') ?></p>
          <p><strong>Formato:</strong> <?= htmlspecialchars($livro['formato'] ?? '-') ?></p>
          <p><strong>Volume:</strong> <?= !empty($livro['volume']) ? htmlspecialchars((string)$livro['volume']) : '-' ?></p>
        </div>
      </div>

      <div class="mt-3">
        <?php if (!empty($livro['categoria_nome'])): ?>
          <span class="badge bg-dark"><?= htmlspecialchars($livro['categoria_nome']) ?></span>
        <?php endif; ?>
        <?php if (!empty($livro['formato'])): ?>
          <span class="badge bg-secondary"><?= htmlspecialchars($livro['formato']) ?></span>
        <?php endif; ?>
        <?php if (!empty($livro['tipo'])): ?>
          <span class="badge bg-info text-dark"><?= htmlspecialchars($livro['tipo']) ?></span>
        <?php endif; ?>
      </div>
    </div>
  </div>
</div>
</body>
</html>
