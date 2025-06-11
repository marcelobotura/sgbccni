// ✅ Arquivo: backend/controllers/auth/register_valida.php
<?php
session_start();
require_once __DIR__ . '/../../../config/env.php';
require_once __DIR__ . '/../../../config/config.php';

$nome = trim($_POST['nome'] ?? '');
$email = strtolower(trim($_POST['email'] ?? ''));
$senha = $_POST['senha'] ?? '';
$senha2 = $_POST['senha2'] ?? '';
$tipo = 'usuario';

if (!$nome || !$email || !$senha || !$senha2) {
    $_SESSION['erro'] = "Todos os campos são obrigatórios.";
    header("Location: " . URL_BASE . "login/register_user.php");
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $_SESSION['erro'] = "E-mail inválido.";
    header("Location: " . URL_BASE . "login/register_user.php");
    exit;
}

if ($senha !== $senha2) {
    $_SESSION['erro'] = "As senhas não coincidem.";
    header("Location: " . URL_BASE . "login/register_user.php");
    exit;
}

$senha_hash = password_hash($senha, PASSWORD_DEFAULT);

$stmt_check = $conn->prepare("SELECT id FROM usuarios WHERE email = ?");
$stmt_check->bind_param("s", $email);
$stmt_check->execute();
$stmt_check->store_result();

if ($stmt_check->num_rows > 0) {
    $_SESSION['erro'] = "E-mail já cadastrado.";
    header("Location: " . URL_BASE . "login/register_user.php");
    exit;
}

$stmt = $conn->prepare("INSERT INTO usuarios (nome, email, senha, tipo) VALUES (?, ?, ?, ?)");
$stmt->bind_param("ssss", $nome, $email, $senha_hash, $tipo);
$stmt->execute();

$_SESSION['sucesso'] = "Cadastro realizado com sucesso.";
header("Location: " . URL_BASE . "login/login_user.php");
exit;
