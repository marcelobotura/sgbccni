<?php
session_start();
require_once __DIR__ . '/../../backend/config/config.php';

// 🔐 Verifica se o formulário foi enviado corretamente
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    $_SESSION['erro'] = "Acesso inválido.";
    header("Location: " . URL_BASE . "frontend/login/register.php");
    exit;
}

// 📥 Coleta e sanitiza os dados
$nome  = trim($_POST['nome'] ?? '');
$email = trim($_POST['email'] ?? '');
$senha = $_POST['senha'] ?? '';

// ✅ Validação básica
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

// 🔍 Verifica se o e-mail já está cadastrado
$stmt = $conn->prepare("SELECT id FROM usuarios WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {
    $_SESSION['erro'] = "Este e-mail já está cadastrado.";
    $stmt->close();
    header("Location: " . URL_BASE . "frontend/login/register.php");
    exit;
}
$stmt->close();

// 🔐 Criptografa a senha
$senha_hash = password_hash($senha, PASSWORD_DEFAULT);
$tipo = 'usuario';

// 💾 Insere o novo usuário
$stmt = $conn->prepare("INSERT INTO usuarios (nome, email, senha, tipo) VALUES (?, ?, ?, ?)");
$stmt->bind_param("ssss", $nome, $email, $senha_hash, $tipo);

if ($stmt->execute()) {
    // 🔐 Cria sessão do usuário
    $_SESSION['usuario_id']   = $stmt->insert_id;
    $_SESSION['usuario_nome'] = htmlspecialchars($nome);
    $_SESSION['usuario_tipo'] = $tipo;

    $_SESSION['sucesso'] = "Cadastro realizado com sucesso!";
    header("Location: " . URL_BASE . "frontend/usuario/meus_livros.php");
} else {
    $_SESSION['erro'] = "Erro ao cadastrar. Tente novamente.";
    header("Location: " . URL_BASE . "frontend/login/register.php");
}
$stmt->close();
exit;
