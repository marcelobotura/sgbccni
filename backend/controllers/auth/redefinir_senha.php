<?php
session_start();

require_once __DIR__ . '/../../../config/env.php';      // Garante que URL_BASE está definida
require_once __DIR__ . '/../../../config/config.php';   // Conexão com o banco

$email = trim($_POST['email'] ?? '');

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $_SESSION['erro'] = "E-mail inválido.";
    header("Location: " . URL_BASE . "login/redefinir_senha.php");
    exit;
}

// 🔍 Verifica se o e-mail está cadastrado
$stmt = $conn->prepare("SELECT id FROM usuarios WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows === 1) {
    $stmt->bind_result($usuario_id);
    $stmt->fetch();

    // 🔐 Gera token de recuperação
    $token = bin2hex(random_bytes(32));
    $expira = date('Y-m-d H:i:s', time() + 3600); // 1 hora

    // 💾 Salva token
    $stmtInsert = $conn->prepare("INSERT INTO tokens_recuperacao (usuario_id, token, expira_em) VALUES (?, ?, ?)");
    $stmtInsert->bind_param("iss", $usuario_id, $token, $expira);
    $stmtInsert->execute();

    // ✉️ Simulação de envio de e-mail (substitua por PHPMailer ou similar)
    $link = URL_BASE . "login/nova_senha.php?token=" . urlencode($token);
    $_SESSION['sucesso'] = "Um link de redefinição foi enviado para seu e-mail.";
    
    // Somente para ambiente de desenvolvimento:
    if (ENV_DEV) {
        $_SESSION['link_simulado'] = $link;
    }

    header("Location: " . URL_BASE . "login/redefinir_senha.php");
    exit;

} else {
    $_SESSION['erro'] = "E-mail não encontrado.";
    header("Location: " . URL_BASE . "login/redefinir_senha.php");
    exit;
}
