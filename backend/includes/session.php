<?php
// Ativa a sessão se ainda não estiver
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

// ✅ Função para verificar se o usuário está logado
function usuario_logado(): bool {
    return isset($_SESSION['usuario_id']);
}

// ✅ Retorna o tipo do usuário logado ('admin' ou 'usuario')
function tipo_usuario(): ?string {
    return $_SESSION['usuario_tipo'] ?? null;
}

// ✅ Exige login e tipo correto
if (!function_exists('exigir_login')) {
    function exigir_login(?string $tipo = null) {
        if (!usuario_logado() || ($tipo && tipo_usuario() !== $tipo)) {
            if ($tipo === 'admin') {
                header('Location: /sgbccni/frontend/login/login_admin.php');
            } else {
                header('Location: /sgbccni/frontend/login/login_user.php');
            }
            exit;
        }
    }
}
