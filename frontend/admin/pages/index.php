<?php
require_once __DIR__ . '/../../../backend/config/config.php';
require_once __DIR__ . '/../../../backend/includes/session.php';

if (!usuario_logado()) {
    header("Location: " . URL_BASE . "login.php");
    exit;
}

if (tipo_usuario() === 'admin') {
    header("Location: " . URL_BASE . "frontend/admin/dashboard.php");
    exit;
}

header("Location: " . URL_BASE . "frontend/usuario/dashboard.php");
exit;
