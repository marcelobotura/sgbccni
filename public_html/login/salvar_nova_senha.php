<?php
define('BASE_PATH', dirname(__DIR__) . '/../app_backend');
require_once BASE_PATH . '/config/config.php';

$token = $_POST['token'] ?? '';
$senha = $_POST['senha'] ?? '';
$confirmar = $_POST['confirmar'] ?? '';

if (!$token || $senha !== $confirmar) {
    die('❌ Dados inválidos.');
}

$stmt = $conn->prepare("SELECT usuario_id FROM tokens_recuperacao WHERE token = ? AND expira_em > NOW()");
$stmt->bind_param("s", $token);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows !== 1) {
    die('❌ Token expirado ou inválido.');
}

$usuario = $result->fetch_assoc();
$hash = password_hash($senha, PASSWORD_DEFAULT);

// Atualiza a senha
$stmt = $conn->prepare("UPDATE usuarios SET senha = ? WHERE id = ?");
$stmt->bind_param("si", $hash, $usuario['usuario_id']);
$stmt->execute();

// Apaga o token
$conn->query("DELETE FROM tokens_recuperacao WHERE token = '$token'");

echo '<h3>✅ Senha redefinida com sucesso! <a href="index.php">Ir para o login</a></h3>';
