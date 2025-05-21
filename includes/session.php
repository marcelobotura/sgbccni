<?php
// Garante sessão
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Define URL_BASE caso ainda não tenha sido definida (evita erro fatal)
if (!defined('URL_BASE')) {
    define('URL_BASE', '/login/'); // fallback mínimo
}

// Verifica login
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
