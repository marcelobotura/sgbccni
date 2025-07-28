<?php
// Caminho: public_html/autenticacao/logout.php

// Garante que a sessão será iniciada somente se ainda não estiver
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Limpa a sessão
$_SESSION = [];

if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

session_destroy();

// Redireciona para o login
require_once __DIR__ . '/../backend/config/config.php';
header("Location: " . URL_BASE . "login.php");
exit;
