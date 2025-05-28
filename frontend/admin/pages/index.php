<?php
require_once __DIR__ . '/../../backend/config/config.php';
require_once __DIR__ . '/../../backend/includes/session.php';

// 🔐 Redirecionamento baseado no tipo de usuário logado
if (isset($_SESSION['usuario_id'])) {
    if ($_SESSION['usuario_tipo'] === 'admin') {
        header("Location: ../../frontend/admin/dashboard.php");
    } else {
        // Redirecionar para painel de usuário comum, se desejar
        header("Location: ../../frontend/usuario/index.php");
    }
    exit;
}

// 🔁 Se não estiver logado, vai para login de admin (pode ser alterado para login geral)
header("Location: ../../public_html/login/login_admin.php");
exit;
