<?php
// Caminho: backend/controllers/autenticacao/logout.php

// Garante que BASE_PATH esteja definida, mesmo se o arquivo for acessado diretamente
if (!defined('BASE_PATH')) {
    define('BASE_PATH', realpath(__DIR__ . '/../../..'));
}

require_once BASE_PATH . '/backend/config/config.php';
session_start();

// Destroi a sessão
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

session_destroy();

// Redireciona para tela de login
header("Location: " . URL_BASE . "frontend/login/login.php");
exit;
