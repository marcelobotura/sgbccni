<?php
define('BASE_PATH', __DIR__);
require_once BASE_PATH . '/../config/config.php';
require_once BASE_PATH . '/../includes/session.php';
include_once BASE_PATH . '/../includes/header.php';

exigir_login('admin');
?>

<div class="container">
  <h2><i class="bi bi-plus-square"></i> Cadastrar Livro</h2>
  <form method="post" action="salvar_livro.php">
    <!-- Campos de título, autor, ISBN, etc. -->
  </form>
</div>

<?php include_once BASE_PATH . '/../includes/footer.php'; ?>
