<?php
session_start();

// Destroi todas as variáveis de sessão
$_SESSION = [];

// Destroi a sessão
session_destroy();

// Opcional: remove cookies de sessão
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// Redireciona para a página inicial
require_once __DIR__ . '/../../backend/config/config.php';
header("Location: " . URL_BASE . "index.php");
exit;
