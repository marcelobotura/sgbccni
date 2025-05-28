<?php
require_once(__DIR__ . '/../config/config.php');
require_once(__DIR__ . '/../includes/header.php');

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($id <= 0) {
  echo '<div class="container mt-5"><div class="alert alert-danger">ID inválido.</div></div>';
  require_once(__DIR__ . '/../includes/footer.php');
  exit;
}

$sql = "SELECT * FROM livros WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$livro = $stmt->get_result()->fetch_assoc();

if (!$livro) {
  echo '<div class="container mt-5"><div class="alert alert-warning">Livro não encontrado.</div></div>';
  require_once(__DIR__ . '/../includes/footer.php');
  exit;
}
?>

<div class="container my-5">
  <div class="card shadow-lg border-0">
    <div class="row g-0">
      <div class="col-md-4">
        <?php if (!empty($livro['capa'])): ?>
          <img src="<?= URL_BASE ?>uploads/capas/<?= htmlspecialchars($livro['capa']) ?>" class="img-fluid h-100 rounded-start" style="object-fit: cover;" alt="Capa do livro">
        <?php elseif (!empty($livro['capa_url'])): ?>
          <img src="<?= htmlspecialchars($livro['capa_url']) ?>" class="img-fluid h-100 rounded-start" style="object-fit: cover;" alt="Capa do livro">
        <?php else: ?>
          <div class="d-flex align-items-center justify-content-center h-100 bg-light text-muted p-4">Sem imagem</div>
        <?php endif; ?>
      </div>
      <div class="col-md-8">
        <div class="card-body">
          <h3><?= htmlspecialchars($livro['titulo']) ?></h3>
          <ul class="list-unstyled">
            <?php if ($livro['autor']): ?><li><strong>Autor:</strong> <?= htmlspecialchars($livro['autor']) ?></li><?php endif; ?>
            <?php if ($livro['editora']): ?><li><strong>Editora:</strong> <?= htmlspecialchars($livro['editora']) ?></li><?php endif; ?>
            <?php if ($livro['ano']): ?><li><strong>Ano:</strong> <?= htmlspecialchars($livro['ano']) ?></li><?php endif; ?>
            <?php if ($livro['isbn']): ?><li><strong>ISBN:</strong> <?= htmlspecialchars($livro['isbn']) ?></li><?php endif; ?>
            <?php if ($livro['formato']): ?><li><strong>Formato:</strong> <?= htmlspecialchars($livro['formato']) ?></li><?php endif; ?>
            <?php if ($livro['idioma']): ?><li><strong>Idioma:</strong> <?= htmlspecialchars($livro['idioma']) ?></li><?php endif; ?>
            <li><strong>Status:</strong>
              <span class="badge bg-<?= $livro['status'] === 'disponivel' ? 'success' : ($livro['status'] === 'reservado' ? 'warning' : 'danger') ?>">
                <?= ucfirst($livro['status']) ?>
              </span>
            </li>
          </ul>

          <?php if ($livro['descricao']): ?>
            <hr><p><strong>Descrição:</strong><br><?= nl2br(htmlspecialchars($livro['descricao'])) ?></p>
          <?php endif; ?>

          <?php if (strtolower($livro['formato']) === 'digital' && !empty($livro['link_leitura'])): ?>
            <a href="<?= htmlspecialchars($livro['link_leitura']) ?>" target="_blank" class="btn btn-success mt-3">
              📖 Ler agora
            </a>
          <?php endif; ?>

          <a href="index.php" class="btn btn-outline-primary mt-3">← Voltar</a>
        </div>
      </div>
    </div>
  </div>
</div>

<?php require_once(__DIR__ . '/../includes/footer.php'); ?>
