<?php
// ✅ Iniciar sessão somente se ainda não estiver ativa
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// ✅ Caminho seguro para config.php
require_once __DIR__ . '/../../backend/config/config.php';

// ✅ Verificação de login e tipo de usuário
if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_tipo'] !== 'usuario') {
    // Redireciona para login do usuário
    header("Location: " . URL_BASE . "frontend/login/login.php");
    exit;
}
