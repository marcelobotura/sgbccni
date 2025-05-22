<?php
session_start();

// 🧹 Limpa todas as variáveis de sessão
$_SESSION = [];

// 🔐 Destroi a sessão
session_unset();
session_destroy();

// 🧼 Opcional: limpa cookies da sessão
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// 🔁 Redireciona para a tela de login
header("Location: http://localhost/sgbccni/public_html/login/index.php");
exit;
