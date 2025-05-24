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
    $stmt->bind_result($usuario_id);
    $stmt->fetch();

    $token = bin2hex(random_bytes(32));
    $expira = date('Y-m-d H:i:s', time() + 3600); // 1 hora de validade

    $stmtInsert = $conn->prepare("INSERT INTO tokens_recuperacao (usuario_id, token, expira_em) VALUES (?, ?, ?)");
    $stmtInsert->bind_param("iss", $usuario_id, $token, $expira);
    $stmtInsert->execute();

    // Simulação de envio de e-mail (substitua por envio real)
    $link = URL_BASE . "login/nova_senha.php?token=" . urlencode($token);
    echo "Link de redefinição enviado: <a href='$link'>$link</a>";
} else {
    echo "E-mail não encontrado.";
}
