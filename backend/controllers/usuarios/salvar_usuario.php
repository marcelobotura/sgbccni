// ✅ Arquivo: backend/controllers/usuarios/salvar_usuario.php
<?php
require_once __DIR__ . '/../../../config/config.php';
require_once __DIR__ . '/../../../includes/session.php';
exigir_login('admin');

$nome   = trim($_POST['nome'] ?? '');
$email  = trim($_POST['email'] ?? '');
$senha  = $_POST['senha'] ?? '';
$tipo   = $_POST['tipo'] ?? 'usuario';

if (!$nome || !$email || !$senha) {
    $_SESSION['erro'] = "Preencha todos os campos obrigatórios.";
    header("Location: " . URL_BASE . "admin/pages/register_admin.php");
    exit;
}

$stmt_check = $conn->prepare("SELECT id FROM usuarios WHERE email = ?");
$stmt_check->bind_param("s", $email);
$stmt_check->execute();
$stmt_check->store_result();

if ($stmt_check->num_rows > 0) {
    $_SESSION['erro'] = "Este e-mail já está cadastrado.";
    header("Location: " . URL_BASE . "admin/pages/register_admin.php");
    exit;
}
$stmt_check->close();

$senha_hash = password_hash($senha, PASSWORD_DEFAULT);
$stmt = $conn->prepare("INSERT INTO usuarios (nome, email, senha, tipo) VALUES (?, ?, ?, ?)");
$stmt->bind_param("ssss", $nome, $email, $senha_hash, $tipo);

if ($stmt->execute()) {
    $_SESSION['sucesso'] = "Usuário cadastrado com sucesso.";
} else {
    $_SESSION['erro'] = "Erro ao cadastrar: " . $stmt->error;
}

header("Location: " . URL_BASE . "admin/pages/register_admin.php");
exit;