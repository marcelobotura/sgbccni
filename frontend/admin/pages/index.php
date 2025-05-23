<?php
require_once __DIR__ . '/../../backend/config/config.php';
require_once __DIR__ . '/../../backend/includes/session.php';

// Redireciona conforme o tipo de usuário logado
if (isset($_SESSION['usuario_id']) && $_SESSION['usuario_tipo'] === 'admin') {
    header("Location: ../../frontend/admin/dashboard.php");
    exit;
} else {
    header("Location: ../../public_html/login/login_admin.php");
    exit;
}
