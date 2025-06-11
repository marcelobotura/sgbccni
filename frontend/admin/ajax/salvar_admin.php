<?php
session_start();
require_once '../../../backend/config/env.php';
require_once '../../../backend/config/config.php';

$nome  = trim($_POST['nome'] ?? '');
$email = trim($_POST['email'] ?? '');
$senha = $_POST['senha'] ?? '';

if ($nome === '' || $email === '' || $senha === '') {
    $_SESSION['erro'] = "Preencha todos os campos obrigatórios.";
    header("Location: ../pages/register_admin.php");
    exit;
}

// Verifica se o e-mail já existe
$stmt = $conn->prepare("SELECT id FROM usuarios WHERE email = :email");
$stmt->bindParam(':email', $email);
$stmt->execute();

if ($stmt->fetch()) {
    $_SESSION['erro'] = "Este e-mail já está em uso.";
    header("Location: ../pages/register_admin.php");
    exit;
}

// Criptografa a senha
$senha_hash = password_hash($senha, PASSWORD_DEFAULT);

// Insere novo admin
$stmt = $conn->prepare("INSERT INTO usuarios (nome, email, senha, tipo) VALUES (:nome, :email, :senha, 'admin')");
$stmt->bindParam(':nome', $nome);
$stmt->bindParam(':email', $email);
$stmt->bindParam(':senha', $senha_hash);

if ($stmt->execute()) {
    $_SESSION['sucesso'] = "Administrador criado com sucesso!";
} else {
    $_SESSION['erro'] = "Erro ao criar administrador.";
}

header("Location: ../pages/register_admin.php");
exit;
