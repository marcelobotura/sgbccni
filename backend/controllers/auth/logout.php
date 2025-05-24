<?php
// 🔐 Encerra a sessão de forma segura

session_start();

// Remove todos os dados da sessão
$_SESSION = [];

// Destroi o cookie da sessão, se existir
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(
        session_name(),
        '',
        time() - 42000,
        $params["path"],
        $params["domain"],
        $params["secure"],
        $params["httponly"]
    );
}

// Destroi a sessão
session_destroy();

// Redireciona para a página de login
header("Location: " . (defined('URL_BASE') ? URL_BASE : '/sgbccni/public_html/') . "login/login.php");
exit;
