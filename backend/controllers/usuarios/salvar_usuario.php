// Conteúdo do backend/controllers/usuarios/salvar_usuario.php com as melhorias sugeridas:
<?php
require_once __DIR__ . '/../../../config/config.php';
require_once __DIR__ . '/../../../includes/session.php';
exigir_login('admin');

$nome  = trim($_POST['nome'] ?? '');
$email = trim($_POST['email'] ?? '');
$senha = $_POST['senha'] ?? '';
$senha2 = $_POST['senha2'] ?? ''; // ✅ NOVO: Confirmação de senha
$tipo  = $_POST['tipo'] ?? 'usuario';

// Validação básica
if (!$nome || !$email || !$senha || !$senha2) { // ✅ Verifica também a senha2
    $_SESSION['erro'] = "Preencha todos os campos obrigatórios.";
    header("Location: " . URL_BASE . "admin/pages/register_admin.php");
    exit;
}

// Validação de e-mail
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $_SESSION['erro'] = "E-mail inválido.";
    header("Location: " . URL_BASE . "admin/pages/register_admin.php");
    exit;
}

// Validação de senha (mínimo)
if (strlen($senha) < 6) {
    $_SESSION['erro'] = "A senha deve conter pelo menos 6 caracteres.";
    header("Location: " . URL_BASE . "admin/pages/register_admin.php");
    exit;
}

// ✅ Validação: Senhas devem ser iguais
if ($senha !== $senha2) {
    $_SESSION['erro'] = "As senhas não coincidem.";
    header("Location: " . URL_BASE . "admin/pages/register_admin.php");
    exit;
}

// ✅ Validação: Tipo de usuário deve ser válido
$tipos_permitidos = ['admin', 'editor'];
if (!in_array($tipo, $tipos_permitidos)) {
    $_SESSION['erro'] = "Tipo de usuário inválido.";
    header("Location: " . URL_BASE . "admin/pages/register_admin.php");
    exit;
}

// Verifica duplicidade
$stmt_check = $conn->prepare("SELECT id FROM usuarios WHERE email = ?");
$stmt_check->bind_param("s", $email);
$stmt_check->execute();
$stmt_check->store_result();

if ($stmt_check->num_rows > 0) {
    $_SESSION['erro'] = "Este e-mail já está cadastrado.";
    $stmt_check->close();
    header("Location: " . URL_BASE . "admin/pages/register_admin.php");
    exit;
}
$stmt_check->close();

// Cadastro
$senha_hash = password_hash($senha, PASSWORD_DEFAULT);

$stmt = $conn->prepare("INSERT INTO usuarios (nome, email, senha, tipo) VALUES (?, ?, ?, ?)");
if (!$stmt) {
    $_SESSION['erro'] = "Erro ao preparar o cadastro: " . $conn->error;
    header("Location: " . URL_BASE . "admin/pages/register_admin.php");
    exit;
}

$stmt->bind_param("ssss", $nome, $email, $senha_hash, $tipo);
if ($stmt->execute()) {
    $_SESSION['sucesso'] = "Usuário '{$nome}' cadastrado como '{$tipo}' com sucesso!";
    header("Location: " . URL_BASE . "admin/pages/usuarios.php"); // Redireciona para a lista de usuários ou dashboard admin
    exit;
} else {
    $_SESSION['erro'] = "Erro ao cadastrar usuário: " . $stmt->error;
    header("Location: " . URL_BASE . "admin/pages/register_admin.php");
    exit;
}

$stmt->close();
$conn->close();
?>