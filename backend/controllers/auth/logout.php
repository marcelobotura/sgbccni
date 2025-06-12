<?php
session_start();

// Captura o tipo de usuário antes de destruir a sessão
$tipo = $_SESSION['usuario_tipo'] ?? null;

// Destroi a sessão
session_unset();
session_destroy();

// Redireciona para o login apropriado
if ($tipo === 'admin') {
    header("Location: /sgbccni/frontend/login/login_admin.php");
} else {
    header("Location: /sgbccni/frontend/login/login_user.php");
}
exit;
