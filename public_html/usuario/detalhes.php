<?php
include_once(__DIR__ . '/../config/config.php');
include_once(__DIR__ . '/../includes/header.php');

// Validar ID
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($id <= 0) {
  echo '<div class="container mt-5"><div class="alert alert-danger">ID inválido.</div></div>';
  include_once(__DIR__ . '/../includes/footer.php');
  exit;
}

// Buscar livro
$sql = "SELECT * FROM livros WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$livro = $stmt->get_result()->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <title><?= htmlspecialchars($livro['titulo'] ?? 'Detalhes do Livro') ?> - Biblioteca CNI</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <link href="<?= URL_BASE ?>assets/css/style.css" rel="stylesheet">
</head>
<body class="<?= $_COOKIE['modo_tema'] ?? 'dark' ?>">

<div class="container my-5">
  <?php if ($livro): ?>
    <div class="card shadow-lg border-0 livro-detalhes">
      <div class="row g-0">
        <div class="col-md-4">
          <?php if (!empty($livro['capa'])): ?>
            <img src="../uploads/capas/<?= htmlspecialchars($livro['capa']) ?>" class="img-fluid h-100 rounded-start object-fit-cover" alt="Capa do livro">
          <?php elseif (!empty($livro['capa_url'])): ?>
            <img src="<?= htmlspecialchars($livro['capa_url']) ?>" class="img-fluid h-100 rounded-start object-fit-cover" alt="Capa do livro">
          <?php else: ?>
            <div class="d-flex align-items-center justify-content-center h-100 bg-light text-muted p-4">
              <span>Sem imagem disponível</span>
            </div>
          <?php endif; ?>
        </div>
        <div class="col-md-8">
          <div class="card-body">
            <h3 class="card-title mb-3">
              <i class="bi bi-book-half info-icon"></i>
              <?= htmlspecialchars($livro['titulo']) ?>
            </h3>

            <ul class="list-unstyled">
              <?php if (!empty($livro['autor'])): ?>
                <li><i class="bi bi-person info-icon"></i> <strong>Autor:</strong> <?= htmlspecialchars($livro['autor']) ?></li>
              <?php endif; ?>

              <?php if (!empty($livro['editora'])): ?>
                <li><i class="bi bi-building info-icon"></i> <strong>Editora:</strong> <?= htmlspecialchars($livro['editora']) ?></li>
              <?php endif; ?>

              <?php if (!empty($livro['ano'])): ?>
                <li><i class="bi bi-calendar info-icon"></i> <strong>Ano:</strong> <?= htmlspecialchars($livro['ano']) ?></li>
              <?php endif; ?>

              <?php if (!empty($livro['isbn'])): ?>
                <li><i class="bi bi-upc-scan info-icon"></i> <strong>ISBN:</strong> <?= htmlspecialchars($livro['isbn']) ?></li>
              <?php endif; ?>

              <?php if (!empty($livro['formato'])): ?>
                <li><i class="bi bi-file-earmark-text info-icon"></i> <strong>Formato:</strong> <?= htmlspecialchars($livro['formato']) ?></li>
              <?php endif; ?>

              <?php if (!empty($livro['idioma'])): ?>
                <li><i class="bi bi-translate info-icon"></i> <strong>Idioma:</strong> <?= htmlspecialchars($livro['idioma']) ?></li>
              <?php endif; ?>

              <?php if (!empty($livro['status'])): ?>
                <li><i class="bi bi-archive info-icon"></i> <strong>Status:</strong>
                  <span class="badge bg-<?= $livro['status'] === 'disponivel' ? 'success' : ($livro['status'] === 'reservado' ? 'warning' : 'danger') ?>">
                    <?= ucfirst($livro['status']) ?>
                  </span>
                </li>
              <?php endif; ?>
            </ul>

            <?php if (!empty($livro['descricao'])): ?>
              <hr>
              <p><i class="bi bi-info-circle info-icon"></i> <strong>Descrição:</strong><br>
                <?= nl2br(htmlspecialchars($livro['descricao'])) ?></p>
            <?php endif; ?>

            <!-- Se for livro digital com link de leitura -->
            <?php if (!empty($livro['formato']) && strtolower($livro['formato']) === 'digital' && !empty($livro['link_leitura'])): ?>
              <a href="<?= htmlspecialchars($livro['link_leitura']) ?>" target="_blank" class="btn btn-success mt-3">
                <i class="bi bi-book"></i> Ler agora
              </a>
            <?php endif; ?>

            <a href="index.php" class="btn btn-outline-primary mt-3">
              <i class="bi bi-arrow-left"></i> Voltar
            </a>
          </div>
        </div>
      </div>
    </div>
  <?php else: ?>
    <div class="alert alert-danger mt-5">⚠️ Livro não encontrado.</div>
  <?php endif; ?>
</div>

<?php include_once(__DIR__ . '/../includes/footer.php'); ?>
</body>
</html>
