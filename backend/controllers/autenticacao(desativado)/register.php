<?php
// Caminho: backend/controllers/autenticacao/register.php

require_once BASE_PATH . '/backend/config/config.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome  = trim(filter_input(INPUT_POST, 'nome', FILTER_SANITIZE_SPECIAL_CHARS));
    $email = trim(filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL));
    $senha = $_POST['senha'] ?? '';
    $tipo  = 'usuario';

    // Validação de campos
    if (empty($nome) || empty($email) || empty($senha)) {
        $_SESSION['erro'] = "Preencha todos os campos.";
        header("Location: " . URL_BASE . "frontend/login/register.php");
        exit;
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['erro'] = "E-mail inválido.";
        header("Location: " . URL_BASE . "frontend/login/register.php");
        exit;
    }

    try {
        // Conexão com o banco
        $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASS);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Verifica se já existe e-mail
        $stmt = $pdo->prepare("SELECT id FROM usuarios WHERE email = ?");
        $stmt->execute([$email]);

        if ($stmt->rowCount() > 0) {
            $_SESSION['erro'] = "Este e-mail já está cadastrado.";
            header("Location: " . URL_BASE . "frontend/login/register.php");
            exit;
        }

        // Inserção
        $senha_hash = password_hash($senha, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("INSERT INTO usuarios (nome, email, senha, tipo) VALUES (?, ?, ?, ?)");
        $stmt->execute([$nome, $email, $senha_hash, $tipo]);

        // Sessão e redirecionamento
        session_regenerate_id(true);
        $_SESSION['usuario_id']   = $pdo->lastInsertId();
        $_SESSION['usuario_nome'] = $nome;
        $_SESSION['usuario_tipo'] = $tipo;

        $_SESSION['registro_sucesso'] = "Cadastro realizado com sucesso!";
        header("Location: " . URL_BASE . "frontend/usuario/index.php");
        exit;

    } catch (PDOException $e) {
        $_SESSION['erro'] = "Erro ao registrar: " . $e->getMessage();
        header("Location: " . URL_BASE . "frontend/login/register.php");
        exit;
    }
}
