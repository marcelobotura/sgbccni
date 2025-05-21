<?php
// Garante que a sessão seja iniciada apenas uma vez.
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Verifica se a URL_BASE está definida. Se não estiver, pode indicar um problema de configuração.
// É esperado que URL_BASE seja definida em config.php
if (!defined('URL_BASE')) {
    error_log('URL_BASE não está definida. Verifique seu arquivo config.php.');
    define('URL_BASE', '/'); // Fallback para evitar erro fatal, mas o ideal é que seja definida no config.
}

function usuario_logado() {
    return isset($_SESSION['usuario_id']);
}

function tipo_usuario() {
    return $_SESSION['usuario_tipo'] ?? null;
}

function exigir_login($tipo_esperado = null, $redirecionar_para = null) {
    if (!usuario_logado() || ($tipo_esperado && tipo_usuario() !== $tipo_esperado)) {
        $destino = $redirecionar_para ?: URL_BASE . 'login/';
        header("Location: $destino");
        exit;
    }
}