<?php
define('BASE_PATH', __DIR__);
require_once BASE_PATH . '/../config/config.php';
require_once BASE_PATH . '/../includes/session.php';
include_once BASE_PATH . '/../includes/header.php';

exigir_login('admin');
?>

<div class="container">
  <h2><i class="bi bi-speedometer2"></i> Painel Administrativo</h2>
  <p>Bem-vindo, <?= htmlspecialchars($_SESSION['usuario_nome']) ?>!</p>
</div>

<?php include_once BASE_PATH . '/../includes/footer.php'; ?>
