<?php
define('BASE_PATH', dirname(__DIR__) . '/app_backend');
require_once BASE_PATH . '/config/config.php';
require_once BASE_PATH . '/includes/session.php';
include_once BASE_PATH . '/includes/header.php';

// Teste opcional
echo "🧪 Página carregada com sucesso";
?>

<div class="container py-5">
  <h1 class="text-center">Bem-vindo à Biblioteca Comunitária CNI</h1>
  <p class="lead text-center">Faça login ou explore o sistema.</p>
</div>

<?php include_once BASE_PATH . '/includes/footer.php'; ?>
