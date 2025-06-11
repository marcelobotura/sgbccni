<?php
session_start();
session_unset();
session_destroy();

// Redirecionar com base no tipo anterior (opcional)
$loginPage = 'login_admin.php';
if (isset($_SESSION['usuario_tipo']) && $_SESSION['usuario_tipo'] === 'usuario') {
    $loginPage = 'login_user.php';
}

header("Location: ../../frontend/login/$loginPage");
exit;
