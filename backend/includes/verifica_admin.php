<?php
// Inicia a sessão, se necessário
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

// Verifica se o usuário está logado e é administrador
if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_tipo'] !== 'admin') {
    // Redireciona para o login de admin
    header('Location: /sgbccni/frontend/login/login_admin.php');
    exit;
}
