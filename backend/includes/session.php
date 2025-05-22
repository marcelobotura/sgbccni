<?php
// ✅ Garante que a sessão seja iniciada apenas uma vez
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// ✅ Garante que URL_BASE esteja definida
if (!defined('URL_BASE')) {
    error_log('[⚠️ AVISO] URL_BASE não está definida. Verifique config.php.');
    define('URL_BASE', '/'); // Fallback mínimo
}

/**
 * ✅ Verifica se há usuário logado
 */
function usuario_logado(): bool {
    return isset($_SESSION['usuario_id']);
}

/**
 * ✅ Retorna o tipo de usuário logado
 */
function tipo_usuario(): ?string {
    return $_SESSION['usuario_tipo'] ?? null;
}

/**
 * 🔐 Exige login, com tipo opcional (ex: admin, usuario)
 * Redireciona para URL de login, se necessário
 */
function exigir_login(?string $tipo_esperado = null, ?string $redirecionar_para = null): void {
    if (!usuario_logado()) {
        header("Location: " . ($redirecionar_para ?? URL_BASE . 'login/index.php'));
        exit;
    }

    if ($tipo_esperado && tipo_usuario() !== $tipo_esperado) {
        error_log("[🔒 BLOQUEADO] Tentativa de acesso por tipo errado. Esperado: $tipo_esperado | Atual: " . tipo_usuario());
        header("Location: " . ($redirecionar_para ?? URL_BASE . 'login/index.php'));
        exit;
    }
}
