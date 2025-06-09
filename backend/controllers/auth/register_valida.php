<?php
session_start();
require_once __DIR__ . '/../../config/config.php'; // Ajuste o caminho conforme necessário

// 🔐 Verifica se o formulário foi enviado corretamente
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    $_SESSION['erro'] = "Acesso inválido.";
    header("Location: " . URL_BASE . "frontend/login/register_usuario.php"); // Corrigido para register_usuario.php
    exit;
}

// 📥 Coleta e sanitiza os dados
$nome  = trim($_POST['nome'] ?? '');
$email = trim($_POST['email'] ?? '');
$senha = $_POST['senha'] ?? '';
$senha2 = $_POST['senha2'] ?? ''; // ✅ NOVO: Confirmação de senha

// Armazena dados no $_SESSION para repopular o formulário em caso de erro
$_SESSION['form_data'] = [
    'nome' => $nome,
    'email' => $email,
    'mensagem' => '' // Adicionado para evitar erro em outros formulários de contato
];

// ✅ Validação básica
if (empty($nome) || empty($email) || empty($senha) || empty($senha2)) { // ✅ Verifica também a senha2
    $_SESSION['erro'] = "Preencha todos os campos obrigatórios.";
    header("Location: " . URL_BASE . "frontend/login/register_usuario.php"); // Corrigido
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $_SESSION['erro'] = "E-mail inválido.";
    header("Location: " . URL_BASE . "frontend/login/register_usuario.php"); // Corrigido
    exit;
}

// ✅ Validação: Senhas devem ser iguais
if ($senha !== $senha2) {
    $_SESSION['erro'] = "As senhas não coincidem.";
    header("Location: " . URL_BASE . "frontend/login/register_usuario.php"); // Corrigido
    exit;
}

// Validação de senha (mínimo, se desejar um mínimo diferente para usuários comuns)
if (strlen($senha) < 6) {
    $_SESSION['erro'] = "A senha deve conter pelo menos 6 caracteres.";
    header("Location: " . URL_BASE . "frontend/login/register_usuario.php");
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
    header("Location: " . URL_BASE . "frontend/login/register_usuario.php"); // Corrigido
    exit;
}
$stmt->close();

// 🔐 Criptografa a senha
$senha_hash = password_hash($senha, PASSWORD_DEFAULT);
$tipo = 'usuario'; // Sempre 'usuario' para este registro

// 💾 Insere o novo usuário
$stmt = $conn->prepare("INSERT INTO usuarios (nome, email, senha, tipo) VALUES (?, ?, ?, ?)");
if (!$stmt) {
    $_SESSION['erro'] = "Erro ao preparar o cadastro: " . $conn->error;
    header("Location: " . URL_BASE . "frontend/login/register_usuario.php"); // Corrigido
    exit;
}

$stmt->bind_param("ssss", $nome, $email, $senha_hash, $tipo);

if ($stmt->execute()) {
    // 🔐 Cria sessão do usuário (opcional, pode redirecionar para login)
    session_regenerate_id(true);
    $_SESSION['usuario_id'] = $conn->insert_id;
    $_SESSION['usuario_nome'] = $nome;
    $_SESSION['usuario_email'] = $email;
    $_SESSION['usuario_tipo'] = $tipo;

    $_SESSION['sucesso'] = "Conta criada com sucesso! Bem-vindo(a) à Biblioteca CNI.";
    header("Location: " . URL_BASE . "frontend/usuario/index.php"); // Redireciona para o dashboard do usuário
    exit;
} else {
    $_SESSION['erro'] = "Erro ao cadastrar usuário: " . $stmt->error;
    header("Location: " . URL_BASE . "frontend/login/register_usuario.php"); // Corrigido
    exit;
}

$stmt->close();
$conn->close();
?>