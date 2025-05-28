<?php
// 🛡️ Gerenciador de sessão e controle de acesso

// Inicia a sessão se ainda não estiver iniciada
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Impede cache de páginas protegidas
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Pragma: no-cache");

// Define URL_BASE apenas se não estiver definida (útil para testes ou páginas isoladas)
if (!defined('URL_BASE')) {
    define('URL_BASE', '/');
}

// ✅ Verifica se o usuário está logado
function usuario_logado(): bool {
    return isset($_SESSION['usuario_id']);
}

// 🔁 Retorna o tipo de usuário ('admin' ou 'usuario')
function tipo_usuario(): ?string {
    return $_SESSION['usuario_tipo'] ?? null;
}

// 🔒 Protege páginas que exigem login e/ou tipo específico
function exigir_login(?string $tipo_esperado = null, ?string $redirecionar_para = null): void {
    if (!usuario_logado() || ($tipo_esperado && tipo_usuario() !== $tipo_esperado)) {
        // log opcional: error_log("Acesso negado de IP " . $_SERVER['REMOTE_ADDR']);
        $destino = $redirecionar_para ?: URL_BASE . 'login/';
        header("Location: $destino");
        exit;
    }
}
