<?php
define('BASE_PATH', dirname(__DIR__) . '/app_backend');
require_once BASE_PATH . '/config/config.php';
require_once BASE_PATH . '/includes/session.php';
include_once BASE_PATH . '/includes/header.php';

exigir_login('usuario');
?>

<div class="container">
  <h2><i class="bi bi-clock-history"></i> Histórico de Empréstimos</h2>
  <!-- Aqui virá a tabela ou cards com os livros emprestados -->
</div>

<?php include_once BASE_PATH . '/includes/footer.php'; ?>
