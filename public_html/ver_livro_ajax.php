<?php
// Caminho: public_html/ver_livro_ajax.php
declare(strict_types=1);

define('BASE_PATH', dirname(__DIR__));
require_once BASE_PATH . '/backend/config/config.php';
require_once BASE_PATH . '/backend/includes/db.php';
require_once BASE_PATH . '/backend/includes/session.php';

$id = (int)($_GET['id'] ?? 0);

if ($id <= 0) {
  exit('<div class="alert alert-danger">Livro inválido.</div>');
}

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
  exit('<div class="alert alert-warning">Livro não encontrado.</div>');
}

function capaLivro(array $livro): string {
  if (!empty($livro['capa_local']) && file_exists(BASE_PATH . '/storage/uploads/capas/' . $livro['capa_local'])) {
    return URL_BASE . 'storage/uploads/capas/' . $livro['capa_local'];
  }
  return $livro['capa_url'] ?? (URL_BASE . 'frontend/assets/img/livro_padrao.png');
}

// Link de pré-visualização
$link_preview = $livro['link_leitura'] ?: '#';

// Verifica login
$logado = isset($_SESSION['usuario_id']);
?>

<div class="col-md-4">
  <img src="<?= capaLivro($livro) ?>" class="img-fluid rounded shadow" alt="Capa do livro">
</div>

<div class="col-md-8">
  <h3><?= htmlspecialchars($livro['titulo']) ?></h3>
  <p class="text-muted mb-1">por <strong><?= htmlspecialchars($livro['autor_nome'] ?? 'Autor desconhecido') ?></strong></p>

  <div class="mb-3 estrelinhas">
    <i class="bi bi-star-fill"></i>
    <i class="bi bi-star-fill"></i>
    <i class="bi bi-star-fill"></i>
    <i class="bi bi-star-half"></i>
    <i class="bi bi-star"></i>
    <a href="#" class="ms-2 text-decoration-none"><i class="bi bi-share"></i> Compartilhar</a>
  </div>

  <div class="d-flex flex-wrap gap-2 mb-3">
    <?php if ($logado): ?>
      <a href="emprestar_livro.php?id=<?= $livro['id'] ?>" class="btn btn-primary"><i class="bi bi-journal-arrow-down"></i> Emprestar</a>
    <?php else: ?>
      <a href="login.php" class="btn btn-primary"><i class="bi bi-person-lock"></i> Entrar para emprestar</a>
    <?php endif; ?>

    <?php if ($livro['tipo'] === 'digital' && $link_preview !== '#'): ?>
      <a href="<?= htmlspecialchars($link_preview) ?>" target="_blank" class="btn btn-outline-secondary"><i class="bi bi-eye"></i> Pré-visualização</a>
    <?php endif; ?>
  </div>

  <p><?= nl2br(htmlspecialchars($livro['descricao'] ?? 'Sem descrição.')) ?></p>

  <?php if (!empty($livro['categoria_nome'])): ?>
    <p><span class="badge bg-secondary"><?= htmlspecialchars($livro['categoria_nome']) ?></span></p>
  <?php endif; ?>

  <a href="ver_livro.php?id=<?= $livro['id'] ?>" class="btn btn-sm btn-link">🔍 Ver mais</a>
</div>
