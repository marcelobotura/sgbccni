<?php
// backend/includes/session.php

// Ativa exibição de erros (recomendado apenas em ambiente de desenvolvimento)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Carrega configuração de ambiente (URL_BASE etc.)
require_once __DIR__ . '/../config/env.php';

// Inicia a sessão, se ainda não iniciada
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

// ✅ Verifica se há um usuário logado
function usuario_logado(): bool {
    return isset($_SESSION['usuario_id']);
}

// ✅ Retorna o tipo do usuário logado ('admin' ou 'usuario'), ou null se não logado
function tipo_usuario(): ?string {
    return $_SESSION['usuario_tipo'] ?? null;
}

// ✅ Exige que o usuário esteja logado, e opcionalmente de um tipo específico
if (!function_exists('exigir_login')) {
    function exigir_login(?string $tipo = null): void {
        if (!usuario_logado() || ($tipo && tipo_usuario() !== $tipo)) {
            // Redireciona conforme o tipo
            if ($tipo === 'admin') {
                header('Location: ' . URL_BASE . '/frontend/login/login_admin.php');
            } else {
                header('Location: ' . URL_BASE . '/frontend/login/login_user.php');
            }
            exit;
        }
    }
}
