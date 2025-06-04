<?php 
session_start();
require_once __DIR__ . '/../../../backend/config/config.php'; // garante que URL_BASE está disponível

if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_tipo'] !== 'admin') {
    header("Location: " . URL_BASE . "login/login_admin.php");
    exit;
}
