<?php
session_start();
require_once __DIR__ . '/../../backend/config/config.php';

if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_tipo'] !== 'usuario') {
    header("Location: " . URL_BASE . "frontend/login/login.php");
    exit;
}
