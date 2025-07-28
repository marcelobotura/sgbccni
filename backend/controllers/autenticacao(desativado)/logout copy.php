<?php
// Caminho: backend/controllers/autenticacao/logout.php

session_start();
session_unset();      // Limpa todas as variáveis de sessão
session_destroy();    // Destrói a sessão

require_once __DIR__ . '/../../config/env.php'; // Garante acesso à constante URL_BASE

// Garante que a constante URL_BASE esteja definida
if (!defined('URL_BASE')) {
    define('URL_BASE', '/sgbccni/');
}

// Redireciona para a página de login
header("Location: " . URL_BASE . "frontend/login/login.php");
exit;
