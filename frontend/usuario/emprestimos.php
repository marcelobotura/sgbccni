<?php
define('BASE_PATH', realpath(__DIR__ . '/../../backend'));
require_once BASE_PATH . '/config/config.php';
require_once BASE_PATH . '/includes/session.php';
require_once BASE_PATH . '/includes/header.php';

exigir_login('usuario');
?>

<div class="container py-4">
  <h2><i class="bi bi-clock-history"></i> Histórico de Empréstimos</h2>
  <div class="alert alert-info mt-4">
    Em breve você poderá visualizar aqui todos os seus livros emprestados e devolvidos.
  </div>
</div>

<?php include_once BASE_PATH . '/includes/footer.php'; ?>
