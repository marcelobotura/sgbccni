<?php
session_start();
require_once __DIR__ . '/../../backend/config/config.php';

// 🚫 Acesso apenas via POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    $_SESSION['erro'] = "Acesso inválido.";
    header("Location: register.php");
    exit;
}

// 📥 Coleta os dados
$nome  = trim($_POST['nome'] ?? '');
$email = trim($_POST['email'] ?? '');
$senha = $_POST['senha'] ?? '';
$tipo  = 'usuario';

// 🔍 Validação básica
if (empty($nome) || empty($email) || empty($senha)) {
    $_SESSION['erro'] = "Preencha todos os campos obrigatórios.";
    header("Location: register.php");
    exit;
}

// 🔁 Verifica se e-mail já está em uso
$stmt = $conn->prepare("SELECT id FROM usuarios WHERE email = ?");
if (!$stmt) {
    $_SESSION['erro'] = "Erro ao preparar a consulta.";
    header("Location: register.php");
    exit;
}

$stmt->bind_param("s", $email);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {
    $_SESSION['erro'] = "Este e-mail já está em uso.";
    $stmt->close();
    header("Location: register.php");
    exit;
}
$stmt->close();

// 🔐 Criptografa a senha
$senha_hash = password_hash($senha, PASSWORD_DEFAULT);

// 💾 Insere novo usuário
$stmt = $conn->prepare("INSERT INTO usuarios (nome, email, senha, tipo) VALUES (?, ?, ?, ?)");
if (!$stmt) {
    $_SESSION['erro'] = "Erro ao preparar o cadastro.";
    header("Location: register.php");
    exit;
}

$stmt->bind_param("ssss", $nome, $email, $senha_hash, $tipo);
if ($stmt->execute()) {
    $_SESSION['usuario_id']   = $stmt->insert_id;
    $_SESSION['usuario_nome'] = $nome;
    $_SESSION['usuario_tipo'] = $tipo;

    $_SESSION['sucesso'] = "Usuário cadastrado com sucesso!";
    header("Location: " . URL_BASE . "usuario/index.php");
} else {
    $_SESSION['erro'] = "Erro ao cadastrar o usuário. Tente novamente.";
    header("Location: register.php");
}

$stmt->close();
exit;
