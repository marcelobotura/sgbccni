// ✅ Arquivo: backend/controllers/auth/redefinir_senha_valida.php
<?php
session_start();
require_once __DIR__ . '/../../../config/env.php';
require_once __DIR__ . '/../../../config/config.php';

$email = filter_var(trim($_POST['email'] ?? ''), FILTER_VALIDATE_EMAIL);

if (!$email) {
    $_SESSION['erro'] = "E-mail inválido.";
    header("Location: " . URL_BASE . "login/redefinir_senha.php");
    exit;
}

$stmt = $conn->prepare("SELECT id, tipo FROM usuarios WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows === 1) {
    $stmt->bind_result($usuario_id, $tipo_usuario);
    $stmt->fetch();

    $token = bin2hex(random_bytes(32));
    $expira = date('Y-m-d H:i:s', time() + 3600);

    $stmtInsert = $conn->prepare("INSERT INTO tokens_recuperacao (usuario_id, token, expira_em) VALUES (?, ?, ?)");
    $stmtInsert->bind_param("iss", $usuario_id, $token, $expira);
    $stmtInsert->execute();

    $_SESSION['sucesso'] = "Um link de redefinição foi enviado para seu e-mail.";
    if (ENV_DEV) {
        $_SESSION['link_simulado'] = URL_BASE . "login/nova_senha.php?token=" . urlencode($token);
    }

    header("Location: " . URL_BASE . "login/redefinir_senha.php");
    exit;

} else {
    $_SESSION['erro'] = "E-mail não encontrado.";
    header("Location: " . URL_BASE . "login/redefinir_senha.php");
    exit;
}