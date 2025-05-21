<?php
define('BASE_PATH', dirname(__DIR__, 2) . '/app_backend');
require_once BASE_PATH . '/config/config.php';
require_once BASE_PATH . '/includes/session.php';
include_once BASE_PATH . '/includes/header.php';

exigir_login('usuario');
?>

<div class="text-end mb-3">
  <a href="<?= URL_BASE ?>login/logout.php" class="btn btn-outline-danger btn-sm">
    <i class="bi bi-box-arrow-right"></i> Sair
  </a>
</div>

<div class="text-center mb-4">
  <h2 class="fw-bold">
    <i class="bi bi-person-circle text-info"></i>
    Olá, <?= htmlspecialchars($_SESSION['usuario_nome']) ?>!
  </h2>
  <p class="lead">Bem-vindo(a) à sua área de leitor(a) da <strong>Biblioteca CNI</strong>.</p>
</div>

<?php include_once BASE_PATH . '/includes/footer.php'; ?>
