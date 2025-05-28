<?php
session_start();
require_once __DIR__ . '/../../backend/config/config.php';

// 🔐 Apenas via POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    $_SESSION['erro'] = "Acesso inválido.";
    header("Location: " . URL_BASE . "login/redefinir_senha.php");
    exit;
}

// 📥 Coleta dados
$email = trim($_POST['email'] ?? '');
$nova_senha = $_POST['nova_senha'] ?? '';

// 🔍 Validação básica
if (empty($email) || empty($nova_senha)) {
    $_SESSION['erro'] = "Preencha todos os campos.";
    header("Location: " . URL_BASE . "login/redefinir_senha.php");
    exit;
}

// 🔎 Verifica se o usuário existe
$stmt = $conn->prepare("SELECT id FROM usuarios WHERE email = ?");
if (!$stmt) {
    $_SESSION['erro'] = "Erro interno. Tente novamente.";
    header("Location: " . URL_BASE . "login/redefinir_senha.php");
    exit;
}
$stmt->bind_param("s", $email);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows === 1) {
    $stmt->bind_result($id);
    $stmt->fetch();
    $stmt->close();

    $senha_hash = password_hash($nova_senha, PASSWORD_DEFAULT);

    // 🔄 Atualiza a senha
    $stmtUpdate = $conn->prepare("UPDATE usuarios SET senha = ? WHERE id = ?");
    if (!$stmtUpdate) {
        $_SESSION['erro'] = "Erro ao preparar atualização de senha.";
        header("Location: " . URL_BASE . "login/redefinir_senha.php");
        exit;
    }

    $stmtUpdate->bind_param("si", $senha_hash, $id);
    if ($stmtUpdate->execute()) {
        $stmtUpdate->close();

        // 📌 Registra LOG de redefinição
        $ip = $_SERVER['REMOTE_ADDR'] ?? 'desconhecido';
        $navegador = substr($_SERVER['HTTP_USER_AGENT'] ?? 'não informado', 0, 250);

        $stmtLog = $conn->prepare("INSERT INTO log_redefinicao_senha (usuario_id, email, ip, navegador) VALUES (?, ?, ?, ?)");
        if ($stmtLog) {
            $stmtLog->bind_param("isss", $id, $email, $ip, $navegador);
            $stmtLog->execute();
            $stmtLog->close();
        }

        $_SESSION['sucesso'] = "Senha redefinida com sucesso. Faça login com sua nova senha.";
        header("Location: " . URL_BASE . "login/login.php");
        exit;
    } else {
        $stmtUpdate->close();
        $_SESSION['erro'] = "Erro ao atualizar a senha. Tente novamente.";
        header("Location: " . URL_BASE . "login/redefinir_senha.php");
        exit;
    }

} else {
    $stmt->close();
    $_SESSION['erro'] = "E-mail não encontrado.";
    header("Location: " . URL_BASE . "login/redefinir_senha.php");
    exit;
}
