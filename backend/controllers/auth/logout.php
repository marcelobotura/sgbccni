<?php
session_start();

// Captura o tipo de usuário antes de destruir a sessão
$tipo = $_SESSION['usuario_tipo'] ?? null;

// Limpa todas as variáveis de sessão
$_SESSION = [];

// Remove o cookie de sessão, se estiver sendo usado
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// Destrói a sessão
session_destroy();

// Redireciona com base no tipo de usuário
if ($tipo === 'admin') {
    header("Location: /sgbccni/frontend/login/login_admin.php");
} else {
    header("Location: /sgbccni/frontend/login/login_user.php");
}
exit;
