<?php
// Inicia sessão apenas se ainda não estiver iniciada
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Define fallback se URL_BASE não estiver definida (evita erro)
if (!defined('URL_BASE')) {
    define('URL_BASE', '/');
}

// Verifica se usuário está logado
function usuario_logado() {
    return isset($_SESSION['usuario_id']);
}

// Retorna tipo do usuário (admin ou usuario)
function tipo_usuario() {
    return $_SESSION['usuario_tipo'] ?? null;
}

// Protege rota com base no tipo
function exigir_login($tipo_esperado = null, $redirecionar_para = null) {
    if (!usuario_logado() || ($tipo_esperado && tipo_usuario() !== $tipo_esperado)) {
        $destino = $redirecionar_para ?: URL_BASE . 'login/';
        header("Location: $destino");
        exit;
    }
}
