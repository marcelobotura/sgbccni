<?php
define('BASE_PATH', dirname(__DIR__) . '/app_backend');
require_once BASE_PATH . '/config/config.php';
require_once BASE_PATH . '/includes/session.php';
include_once BASE_PATH . '/includes/header.php';

exigir_login('usuario');
?>

<h2>Painel do Usuário</h2>

<?php include_once BASE_PATH . '/includes/footer.php'; ?>
