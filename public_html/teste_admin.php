<?php
// ⚠️ ACESSO PROVISÓRIO - IGNORA LOGIN

require_once __DIR__ . '/../app_backend/config/config.php';
session_start();

// Sessão falsa de admin
$_SESSION['usuario_id'] = 9999;
$_SESSION['usuario_nome'] = 'Admin Teste';
$_SESSION['usuario_tipo'] = 'admin';

// Redireciona para o painel do admin
header("Location: admin/index.php");
exit;