<?php
session_start();
require_once __DIR__ . '/../../../config/config.php';

// ✅ Garante que seja via POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    $_SESSION['erro'] = "Acesso inválido.";
    header("Location: " . URL_BASE . "login/redefinir_senha.php");
    exit;
}

// 📥 Coleta dados
$token = $_GET['token'] ?? '';
$nova_senha = $_POST['senha'] ?? '';
$confirmar_senha = $_POST['senha2'] ?? '';
$tipo = $_POST['tipo'] ?? 'usuario'; // padrão: usuario

// 🔍 Validações
if (empty($token) || empty($nova_senha) || empty($confirmar_senha)) {
    $_SESSION['erro'] = "Preencha todos os campos e forneça um token válido.";
    header("Location: " . URL_BASE . "login/redefinir_senha.php");
    exit;
}

if ($nova_senha !== $confirmar_senha) {
    $_SESSION['erro'] = "As senhas não coincidem.";
    header("Location: " . URL_BASE . "login/nova_senha.php?token=" . urlencode($token));
    exit;
}

if (strlen($nova_senha) < 6) {
    $_SESSION['erro'] = "A nova senha deve conter pelo menos 6 caracteres.";
    header("Location: " . URL_BASE . "login/nova_senha.php?token=" . urlencode($token));
    exit;
}

// 🔐 Verifica o token
$stmt_token = $conn->prepare("SELECT usuario_id FROM tokens_recuperacao WHERE token = ? AND expira_em > NOW()");
if (!$stmt_token) {
    $_SESSION['erro'] = "Erro ao acessar o token.";
    header("Location: " . URL_BASE . "login/redefinir_senha.php");
    exit;
}
$stmt_token->bind_param("s", $token);
$stmt_token->execute();
$stmt_token->store_result();

if ($stmt_token->num_rows !== 1) {
    $_SESSION['erro'] = "Token inválido ou expirado.";
    $stmt_token->close();
    header("Location: " . URL_BASE . "login/redefinir_senha.php");
    exit;
}
$stmt_token->bind_result($usuario_id);
$stmt_token->fetch();
$stmt_token->close();

// ❌ Invalida o token
$stmt_del = $conn->prepare("DELETE FROM tokens_recuperacao WHERE token = ?");
$stmt_del->bind_param("s", $token);
$stmt_del->execute();
$stmt_del->close();

// 🔐 Cria hash da nova senha
$senha_hash = password_hash($nova_senha, PASSWORD_DEFAULT);

// 🔄 Atualiza senha
$stmtUpdate = $conn->prepare("UPDATE usuarios SET senha = ? WHERE id = ?");
if (!$stmtUpdate) {
    $_SESSION['erro'] = "Erro ao preparar atualização de senha.";
    header("Location: " . URL_BASE . "login/redefinir_senha.php");
    exit;
}
$stmtUpdate->bind_param("si", $senha_hash, $usuario_id);

if ($stmtUpdate->execute()) {
    $stmtUpdate->close();

    // 📝 Log de redefinição
    try {
        $ip = $_SERVER['REMOTE_ADDR'] ?? 'desconhecido';
        $navegador = substr($_SERVER['HTTP_USER_AGENT'] ?? 'desconhecido', 0, 250);
        $email_usuario_log = '';

        $stmt_email = $conn->prepare("SELECT email FROM usuarios WHERE id = ?");
        if ($stmt_email) {
            $stmt_email->bind_param("i", $usuario_id);
            $stmt_email->execute();
            $stmt_email->bind_result($email_usuario_log);
            $stmt_email->fetch();
            $stmt_email->close();
        }

        $stmtLog = $conn->prepare("INSERT INTO log_redefinicao_senha (usuario_id, email, ip, navegador, token_utilizado, tipo_usuario) VALUES (?, ?, ?, ?, ?, ?)");
        if ($stmtLog) {
            $stmtLog->bind_param("isssss", $usuario_id, $email_usuario_log, $ip, $navegador, $token, $tipo);
            $stmtLog->execute();
            $stmtLog->close();
        }
    } catch (Exception $e) {
        error_log("Erro ao registrar log de redefinição: " . $e->getMessage());
    }

    $_SESSION['sucesso'] = "Senha redefinida com sucesso. Faça login com sua nova senha.";

    // 🔁 Redireciona para o login correto
    if ($tipo === 'admin') {
        header("Location: " . URL_BASE . "login/login_admin.php");
    } else {
        header("Location: " . URL_BASE . "login/login_user.php");
    }

    exit;

} else {
    $stmtUpdate->close();
    $_SESSION['erro'] = "Erro ao atualizar a senha.";
    header("Location: " . URL_BASE . "login/nova_senha.php?token=" . urlencode($token));
    exit;
}

$conn->close();
