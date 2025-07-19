<?php
// Caminho: backend/controllers/auth/register_valida.php

define('BASE_PATH', dirname(__DIR__, 2));
require_once BASE_PATH . '/config/config.php';
require_once BASE_PATH . '/config/env.php';
require_once BASE_PATH . '/includes/db.php';

session_start();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nome    = trim($_POST['nome'] ?? '');
    $email   = trim($_POST['email'] ?? '');
    $senha   = trim($_POST['senha'] ?? '');
    $senha2  = trim($_POST['senha2'] ?? '');
    $tipo    = trim($_POST['tipo'] ?? 'usuario');

    // Salvar os dados parciais para repopular o formulário em caso de erro
    $_SESSION['form_data'] = [
        'nome'  => $nome,
        'email' => $email
    ];

    // Verificações básicas
    if (empty($nome) || empty($email) || empty($senha) || empty($senha2)) {
        $_SESSION['erro'] = "Preencha todos os campos.";
        header("Location: " . URL_BASE . "frontend/login/register_user.php");
        exit;
    }

    if ($senha !== $senha2) {
        $_SESSION['erro'] = "As senhas não coincidem.";
        header("Location: " . URL_BASE . "frontend/login/register_user.php");
        exit;
    }

    try {
        // Verificar se e-mail já está cadastrado
        $stmt = $pdo->prepare("SELECT id FROM usuarios WHERE email = ?");
        $stmt->execute([$email]);

        if ($stmt->rowCount() > 0) {
            $_SESSION['erro'] = "E-mail já cadastrado.";
            header("Location: " . URL_BASE . "frontend/login/register_user.php");
            exit;
        }

        // Inserir usuário
        $senha_hash = password_hash($senha, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("INSERT INTO usuarios (nome, email, senha, tipo) VALUES (?, ?, ?, ?)");
        $stmt->execute([$nome, $email, $senha_hash, $tipo]);

        $_SESSION['sucesso'] = "Cadastro realizado com sucesso! Faça login.";
        unset($_SESSION['form_data']); // Limpar os dados do formulário

        // Redirecionamento pós cadastro
        header("Location: " . URL_BASE . "frontend/login/login_user.php");
        exit;

    } catch (PDOException $e) {
        $_SESSION['erro'] = "Erro no cadastro: " . $e->getMessage();
        header("Location: " . URL_BASE . "frontend/login/register_user.php");
        exit;
    }

} else {
    header("Location: " . URL_BASE . "frontend/login/register_user.php");
    exit;
}
