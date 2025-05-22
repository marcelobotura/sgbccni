// ✅ Arquivo: backend/controllers/auth/redefinir_senha.php
<?php
require_once __DIR__ . '/../../../config/config.php';

$email = trim($_POST['email'] ?? '');

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    die("E-mail inválido.");
}

// Verifica se o e-mail está cadastrado
$stmt = $conn->prepare("SELECT id FROM usuarios WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows === 1) {
    $token = bin2hex(random_bytes(32));
    $expira = date('Y-m-d H:i:s', time() + 3600); // 1 hora

    $stmt->bind_result($usuario_id);
    $stmt->fetch();

    $stmtInsert = $conn->prepare("INSERT INTO tokens_recuperacao (usuario_id, token, expira_em) VALUES (?, ?, ?)");
    $stmtInsert->bind_param("iss", $usuario_id, $token, $expira);
    $stmtInsert->execute();

    // Aqui você enviaria o e-mail com o link:
    // exemplo: https://seusite.com/login/salvar_nova_senha.php?token=abc123
    echo "Link de redefinição enviado. Token: " . $token;
} else {
    echo "E-mail não encontrado.";
}

// ✅ Arquivo: login/salvar_nova_senha.php
<?php
require_once __DIR__ . '/../../config/config.php';

$token = $_GET['token'] ?? '';
$nova_senha = $_POST['senha'] ?? '';

// Verifica se token existe e está válido
$stmt = $conn->prepare("SELECT usuario_id FROM tokens_recuperacao WHERE token = ? AND expira_em > NOW()");
$stmt->bind_param("s", $token);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows === 1) {
    $stmt->bind_result($usuario_id);
    $stmt->fetch();

    $nova_hash = password_hash($nova_senha, PASSWORD_DEFAULT);
    $stmtUpdate = $conn->prepare("UPDATE usuarios SET senha = ? WHERE id = ?");
    $stmtUpdate->bind_param("si", $nova_hash, $usuario_id);
    $stmtUpdate->execute();

    // Remove token usado
    $stmtDel = $conn->prepare("DELETE FROM tokens_recuperacao WHERE token = ?");
    $stmtDel->bind_param("s", $token);
    $stmtDel->execute();

    echo "Senha redefinida com sucesso.";
} else {
    echo "Token inválido ou expirado.";
}
