<?php
// 🚨 Este arquivo deve ser incluído em páginas protegidas

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

/**
 * 🔐 Exige login com base no tipo de usuário (admin ou usuario)
 */
function exigir_login($tipo_requerido = 'usuario') {
    if (!isset($_SESSION['usuario_id'], $_SESSION['usuario_tipo'])) {
        redirecionar_login($tipo_requerido);
    }

    // Se tipo não confere
    if ($_SESSION['usuario_tipo'] !== $tipo_requerido) {
        redirecionar_login($tipo_requerido);
    }
}

/**
 * 🚪 Redireciona para a tela de login apropriada
 */
function redirecionar_login($tipo) {
    $destino = ($tipo === 'admin') ? 'login/login_admin.php' : 'login/index.php';
    header("Location: " . URL_BASE . $destino);
    exit;
}

/**
 * ❓ Verifica se há alguém logado (sem forçar saída)
 */
function usuario_logado() {
    return isset($_SESSION['usuario_id'], $_SESSION['usuario_tipo']);
}
