<?php
require_once __DIR__ . '/../../backend/config/config.php';
require_once __DIR__ . '/../../backend/includes/session.php';

// Redireciona automaticamente com base no tipo de login
if (isset($_SESSION['usuario_id'])) {
  if ($_SESSION['usuario_tipo'] === 'admin') {
    header("Location: ../../frontend/admin/dashboard.php");
  } else {
    header("Location: ../../frontend/usuario/index.php");
  }
  exit;
}

// Se não estiver logado, redireciona para login comum
header("Location: login.php");
exit;
