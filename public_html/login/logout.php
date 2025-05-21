<?php
define('BASE_PATH', dirname(__DIR__, 2) . '/app_backend');
require_once BASE_PATH . '/config/config.php';

session_start();
session_unset();     // limpa todas as variáveis da sessão
session_destroy();   // destrói a sessão

// Remove o cookie de sessão (opcional, mas ajuda)
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// ✅ Redireciona para uma página pública (ex: home)
header("Location: " . URL_BASE . "index.php");
exit;
