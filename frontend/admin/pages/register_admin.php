<?php
session_start();

define('BASE_PATH', dirname(__DIR__, 3) . '/backend');
require_once BASE_PATH . '/config/config.php';
require_once BASE_PATH . '/includes/session.php';
require_once __DIR__ . 'protect_admin.php';

// 🔒 Protege a rota para apenas admins
exigir_login('admin');

// 📥 Coleta os dados
$nome  = trim($_POST['nome'] ?? '');
$email = trim($_POST['email'] ?? '');
$senha = $_POST['senha'] ?? '';

// 🔍 Validações básicas
if ($nome === '' || $email === '' || $senha === '') {
    $_SESSION['erro'] = "Preencha todos os campos obrigatórios.";
    header("Location: cadastrar_admin.php");
    exit;
}

// 🔁 Verifica se e-mail já está cadastrado
$stmt = $conn->prepare("SELECT id FROM usuarios WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {
    $_SESSION['erro'] = "Este e-mail já está em uso.";
    $stmt->close();
    header("Location: cadastrar_admin.php");
    exit;
}
$stmt->close();

// 🔐 Criptografa senha
$senha_hash = password_hash($senha, PASSWORD_DEFAULT);

// 💾 Insere novo admin
$stmt = $conn->prepare("INSERT INTO usuarios (nome, email, senha, tipo) VALUES (?, ?, ?, 'admin')");
$stmt->bind_param("sss", $nome, $email, $senha_hash);

if ($stmt->execute()) {
    $_SESSION['sucesso'] = "Administrador criado com sucesso!";
} else {
    $_SESSION['erro'] = "Erro ao criar administrador.";
}
$stmt->close();

// 🔁 Redireciona
header("Location: cadastrar_admin.php");
exit;
