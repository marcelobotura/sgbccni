<?php
session_start();

require_once __DIR__ . '/../../config/env.php';
require_once __DIR__ . '/../../config/config.php';

$email = trim($_POST['email'] ?? '');

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $_SESSION['erro'] = "E-mail inválido.";
    header("Location: " . URL_BASE . "login/redefinir_senha.php");
    exit;
}

// 🔍 Verifica se o e-mail existe e obtém o tipo
$stmt = $conn->prepare("SELECT id, tipo FROM usuarios WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows === 1) {
    $stmt->bind_result($usuario_id, $tipo_usuario);
    $stmt->fetch();

    // 🔐 Gera token
    $token = bin2hex(random_bytes(32));
    $expira = date('Y-m-d H:i:s', time() + 3600); // 1 hora

    // 💾 Armazena token
    $stmtInsert = $conn->prepare("INSERT INTO tokens_recuperacao (usuario_id, token, expira_em) VALUES (?, ?, ?)");
    $stmtInsert->bind_param("iss", $usuario_id, $token, $expira);
    $stmtInsert->execute();

    // ✉️ Gera link com tipo de usuário embutido
    $link = URL_BASE . "login/nova_senha.php?token=" . urlencode($token) . "&tipo=" . urlencode($tipo_usuario);

    $_SESSION['sucesso'] = "Um link de redefinição foi enviado para seu e-mail.";

    // Para devs, simula envio
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
