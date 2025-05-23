<?php
define('BASE_PATH', dirname(__DIR__) . '/../backend');
require_once BASE_PATH . '/config/config.php';
require_once BASE_PATH . '/includes/session.php';

exigir_login('usuario');

$id = $_SESSION['usuario_id'];

// Exclui do banco
$stmt = $conn->prepare("DELETE FROM usuarios WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();

// Destroi a sessão
session_unset();
session_destroy();

header("Location: ../login/index.php");
exit;
