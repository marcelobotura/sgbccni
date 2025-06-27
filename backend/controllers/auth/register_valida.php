<?php
session_start();
require_once __DIR__ . '/../../config/env.php';
require_once __DIR__ . '/../../config/config.php';

define('URL_BASE', '/sgbccni/'); // ✅ Corrige o erro da linha 30

$nome = trim($_POST['nome'] ?? '');
$email = strtolower(trim($_POST['email'] ?? ''));
$senha = $_POST['senha'] ?? '';
$senha2 = $_POST['senha2'] ?? '';
$origem = $_POST['origem'] ?? 'usuario';
$loginPage = ($origem === 'admin') ? 'login_admin.php' : 'login_user.php';
$registerPage = ($origem === 'admin') ? 'register_admin.php' : 'register_user.php';
$tipo = ($origem === 'admin') ? 'admin' : 'usuario';

$_SESSION['form_data'] = ['nome' => $nome, 'email' => $email];

// Validações
if (!$nome || !$email || !$senha || !$senha2) {
    $_SESSION['erro'] = "Todos os campos são obrigatórios.";
    header("Location: " . URL_BASE . "login/" . $registerPage);
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $_SESSION['erro'] = "E-mail inválido.";
    header("Location: " . URL_BASE . "login/" . $registerPage);
    exit;
}

if ($senha !== $senha2) {
    $_SESSION['erro'] = "As senhas não coincidem.";
    header("Location: " . URL_BASE . "login/" . $registerPage);
    exit;
}

if (strlen($senha) < 6) {
    $_SESSION['erro'] = "A senha deve ter pelo menos 6 caracteres.";
    header("Location: " . URL_BASE . "login/" . $registerPage);
    exit;
}

$senha_hash = password_hash($senha, PASSWORD_DEFAULT);

// Impede cadastro cruzado: só permite admin no formulário de admin e usuario no de usuario
if (($origem === 'admin' && $tipo !== 'admin') || ($origem === 'usuario' && $tipo !== 'usuario')) {
    $_SESSION['erro'] = "Cadastro inválido.";
    header("Location: " . URL_BASE . "login/" . $registerPage);
    exit;
}

try {
    $stmt_check = $conn->prepare("SELECT id FROM usuarios WHERE email = :email");
    $stmt_check->bindParam(':email', $email);
    $stmt_check->execute();

    if ($stmt_check->rowCount() > 0) {
        $_SESSION['erro'] = "E-mail já cadastrado.";
        header("Location: " . URL_BASE . "login/" . $registerPage);
        exit;
    }

    $stmt = $conn->prepare("INSERT INTO usuarios (nome, email, senha, tipo) VALUES (:nome, :email, :senha, :tipo)");
    $stmt->bindParam(':nome', $nome);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':senha', $senha_hash);
    $stmt->bindParam(':tipo', $tipo);
    $stmt->execute();

    unset($_SESSION['form_data']);
    $_SESSION['sucesso'] = "Cadastro realizado com sucesso.";
    header("Location: " . URL_BASE . "login/" . $loginPage);
    exit;

} catch (PDOException $e) {
    $_SESSION['erro'] = "Erro ao cadastrar: " . $e->getMessage();
    header("Location: " . URL_BASE . "login/" . $registerPage);
    exit;
}