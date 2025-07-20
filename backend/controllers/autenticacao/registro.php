<?php
// Caminho: backend/controllers/autenticacao/registro.php

session_start();
require_once __DIR__ . '/../../config/env.php';
require_once __DIR__ . '/../../includes/session.php';
require_once __DIR__ . '/../../config/config.php'; // onde está definido $pdo

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome  = trim(filter_input(INPUT_POST, 'nome', FILTER_SANITIZE_STRING));
    $email = trim(filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL));
    $senha = $_POST['senha'];
    $tipo  = 'usuario';

    if (empty($nome) || empty($email) || empty($senha)) {
        $_SESSION['erro'] = "Preencha todos os campos.";
        header("Location: " . URL_BASE . "login/register.php");
        exit;
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['erro'] = "E-mail inválido.";
        header("Location: " . URL_BASE . "login/register.php");
        exit;
    }

    try {
        $stmt = $pdo->prepare("SELECT id FROM usuarios WHERE email = ?");
        $stmt->execute([$email]);

        if ($stmt->rowCount() > 0) {
            $_SESSION['erro'] = "Este e-mail já está cadastrado.";
            header("Location: " . URL_BASE . "login/register.php");
            exit;
        }

        $senha_hash = password_hash($senha, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("INSERT INTO usuarios (nome, email, senha, tipo) VALUES (?, ?, ?, ?)");
        $stmt->execute([$nome, $email, $senha_hash, $tipo]);

        session_regenerate_id(true);
        $_SESSION['usuario_id']   = $pdo->lastInsertId();
        $_SESSION['usuario_nome'] = htmlspecialchars($nome);
        $_SESSION['usuario_tipo'] = $tipo;

        header("Location: " . URL_BASE . "usuario/meus_livros.php");
        exit;

    } catch (PDOException $e) {
        $_SESSION['erro'] = "Erro ao registrar: " . $e->getMessage();
        header("Location: " . URL_BASE . "login/register.php");
        exit;
    }
}
