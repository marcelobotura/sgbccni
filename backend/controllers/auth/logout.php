<?php
// 🔐 Encerra a sessão de forma segura

require_once __DIR__ . '/../../config/env.php';
session_start();

// Salva o tipo antes de destruir a sessão
$tipo_usuario = $_SESSION['usuario_tipo'] ?? null;

// Limpa os dados da sessão
$_SESSION = [];

// Remove o cookie de sessão, se existir
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

// Gera novo ID de sessão para segurança
session_start();
session_regenerate_id(true);

// Redireciona conforme o tipo de usuário
if ($tipo_usuario === 'admin') {
    header("Location: " . URL_BASE . "frontend/login/login_admin.php");
} else {
    header("Location: " . URL_BASE . "frontend/login/login.php");
}
exit;
