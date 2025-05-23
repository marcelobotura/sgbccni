<?php
session_start();
require_once __DIR__ . '/../../backend/config/config.php';
require_once __DIR__ . '/../../backend/includes/session.php';

// 🔒 Somente administradores podem acessar
exigir_login('admin');

// Verifica se o formulário foi enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome  = trim($_POST['nome'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $senha = $_POST['senha'] ?? '';
    $tipo  = 'admin';

    // Validação
    if (empty($nome) || empty($email) || empty($senha)) {
        $_SESSION['erro'] = "Preencha todos os campos.";
        header("Location: register_admin.php");
        exit;
    }

    // Verifica se o e-mail já está em uso
    $stmt = $conn->prepare("SELECT id FROM usuarios WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $_SESSION['erro'] = "E-mail já cadastrado.";
        $stmt->close();
        header("Location: register_admin.php");
        exit;
    }
    $stmt->close();

    // Cadastra novo administrador
    $senha_hash = password_hash($senha, PASSWORD_DEFAULT);
    $stmt = $conn->prepare("INSERT INTO usuarios (nome, email, senha, tipo) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $nome, $email, $senha_hash, $tipo);

    if ($stmt->execute()) {
        $_SESSION['sucesso'] = "Administrador cadastrado com sucesso!";
    } else {
        $_SESSION['erro'] = "Erro ao cadastrar administrador.";
    }

    $stmt->close();
    header("Location: register_admin.php");
    exit;
} else {
    $_SESSION['erro'] = "Acesso inválido.";
    header("Location: register_admin.php");
    exit;
}
