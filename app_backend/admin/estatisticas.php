<?php
define('BASE_PATH', __DIR__);
require_once BASE_PATH . '/../config/config.php';
require_once BASE_PATH . '/../includes/session.php';
include_once BASE_PATH . '/../includes/header.php';

exigir_login('admin');
?>

<div class="container">
  <h2><i class="bi bi-graph-up"></i> Estatísticas da Biblioteca</h2>
  <!-- Gráficos, contadores, relatórios PDF -->
</div>

<?php include_once BASE_PATH . '/../includes/footer.php'; ?>
