<?php
define('BASE_PATH', dirname(__DIR__) . '/../app_backend');
require_once BASE_PATH . '/config/config.php';
require_once BASE_PATH . '/includes/session.php';
include_once BASE_PATH . '/includes/header.php';

exigir_login('usuario');
?>

<div class="container py-5">
  <div class="row justify-content-between align-items-center mb-4">
    <div class="col-md-8">
      <h2 class="fw-bold">👋 Olá, <?= htmlspecialchars($_SESSION['usuario_nome']) ?></h2>
      <p class="text-muted">Bem-vindo à sua área de leitura da <strong>Biblioteca CNI</strong>.</p>
    </div>
    <div class="col-md-4 text-end">
      <a href="<?= URL_BASE ?>login/logout.php" class="btn btn-outline-danger">
        <i class="bi bi-box-arrow-right"></i> Sair
      </a>
    </div>
  </div>

  <div class="row g-4">
    <div class="col-md-4">
      <a href="livros.php" class="text-decoration-none">
        <div class="card shadow-sm border-0 text-center p-3">
          <h5>📚 Meus Livros</h5>
          <p class="text-muted">Estante de leitura atual</p>
        </div>
      </a>
    </div>
    <div class="col-md-4">
      <a href="favoritos.php" class="text-decoration-none">
        <div class="card shadow-sm border-0 text-center p-3">
          <h5>⭐ Favoritos</h5>
          <p class="text-muted">Livros que você marcou</p>
        </div>
      </a>
    </div>
    <div class="col-md-4">
      <a href="historico.php" class="text-decoration-none">
        <div class="card shadow-sm border-0 text-center p-3">
          <h5>🕓 Histórico</h5>
          <p class="text-muted">Leituras anteriores</p>
        </div>
      </a>
    </div>
  </div>
</div>

<?php include_once BASE_PATH . '/includes/footer.php'; ?>
