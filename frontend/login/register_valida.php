<?php
session_start();
require_once __DIR__ . '/../../backend/config/config.php';

// Verifica se o formulário foi enviado corretamente
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    $_SESSION['erro'] = "Acesso inválido.";
    header("Location: register.php");
    exit;
}

// Coleta e limpa os dados
$nome  = trim($_POST['nome'] ?? '');
$email = trim($_POST['email'] ?? '');
$senha = $_POST['senha'] ?? '';

// Valida os campos obrigatórios
if ($nome === '' || $email === '' || $senha === '') {
    $_SESSION['erro'] = "Preencha todos os campos.";
    header("Location: register.php");
    exit;
}

// Verifica se o e-mail já está em uso
$stmt = $conn->prepare("SELECT id FROM usuarios WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {
    $_SESSION['erro'] = "Este e-mail já está cadastrado.";
    $stmt->close();
    header("Location: register.php");
    exit;
}
$stmt->close();

// Criptografa a senha
$senha_hash = password_hash($senha, PASSWORD_DEFAULT);
$tipo = 'usuario';

// Insere o novo usuário
$stmt = $conn->prepare("INSERT INTO usuarios (nome, email, senha, tipo) VALUES (?, ?, ?, ?)");
$stmt->bind_param("ssss", $nome, $email, $senha_hash, $tipo);

if ($stmt->execute()) {
    $_SESSION['usuario_id']   = $stmt->insert_id;
    $_SESSION['usuario_nome'] = $nome;
    $_SESSION['usuario_tipo'] = $tipo;

    $_SESSION['sucesso'] = "Cadastro realizado com sucesso!";
    header("Location: " . URL_BASE . "frontend/usuario/meus_livros.php");
} else {
    $_SESSION['erro'] = "Erro ao cadastrar. Tente novamente.";
    header("Location: register.php");
}
$stmt->close();
exit;
