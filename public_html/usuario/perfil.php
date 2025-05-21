<?php
define('BASE_PATH', dirname(__DIR__) . '/app_backend');
require_once BASE_PATH . '/config/config.php';
require_once BASE_PATH . '/includes/session.php';
include_once BASE_PATH . '/includes/header.php';

exigir_login('usuario');
?>

<div class="container">
  <h2><i class="bi bi-person-lines-fill"></i> Meu Perfil</h2>
  <p><strong>Nome:</strong> <?= htmlspecialchars($_SESSION['usuario_nome']) ?></p>
  <p><strong>Email:</strong> <?= htmlspecialchars($_SESSION['usuario_email']) ?></p>
</div>

<?php include_once BASE_PATH . '/includes/footer.php'; ?>
