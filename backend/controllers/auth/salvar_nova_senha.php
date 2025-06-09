<?php
session_start();
require_once __DIR__ . '/../../config/config.php';

// 🔐 Apenas via POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    $_SESSION['erro'] = "Acesso inválido.";
    header("Location: " . URL_BASE . "login/redefinir_senha.php");
    exit;
}

// 📥 Coleta dados
$token = $_GET['token'] ?? '';
$nova_senha = $_POST['senha'] ?? ''; // Alterado de nova_senha para senha (para consistência)
$confirmar_senha = $_POST['senha2'] ?? ''; // ✅ NOVO: Confirmação de senha

// 🔍 Validação básica
if (empty($token) || empty($nova_senha) || empty($confirmar_senha)) { // ✅ Verifica também a confirmação
    $_SESSION['erro'] = "Preencha todos os campos e forneça um token válido.";
    header("Location: " . URL_BASE . "login/redefinir_senha.php");
    exit;
}

// ✅ Validação: Senhas devem ser iguais
if ($nova_senha !== $confirmar_senha) {
    $_SESSION['erro'] = "As senhas não coincidem.";
    // Redireciona de volta para a página de nova_senha.php com o token
    header("Location: " . URL_BASE . "login/nova_senha.php?token=" . urlencode($token));
    exit;
}

// Validação de senha (mínimo)
if (strlen($nova_senha) < 6) {
    $_SESSION['erro'] = "A nova senha deve conter pelo menos 6 caracteres.";
    header("Location: " . URL_BASE . "login/nova_senha.php?token=" . urlencode($token));
    exit;
}

// 🔎 Verifica e invalida o token
$stmt_token = $conn->prepare("SELECT usuario_id FROM tokens_recuperacao WHERE token = ? AND expira_em > NOW()");
if (!$stmt_token) {
    $_SESSION['erro'] = "Erro ao preparar a consulta do token.";
    header("Location: " . URL_BASE . "login/redefinir_senha.php");
    exit;
}
$stmt_token->bind_param("s", $token);
$stmt_token->execute();
$stmt_token->store_result();

if ($stmt_token->num_rows === 1) {
    $stmt_token->bind_result($usuario_id);
    $stmt_token->fetch();
    $stmt_token->close();

    // Invalida o token para que não possa ser reutilizado
    $stmtDeleteToken = $conn->prepare("DELETE FROM tokens_recuperacao WHERE token = ?");
    $stmtDeleteToken->bind_param("s", $token);
    $stmtDeleteToken->execute();
    $stmtDeleteToken->close();

    // 🔐 Hash da nova senha
    $senha_hash = password_hash($nova_senha, PASSWORD_DEFAULT);

    // 🔄 Atualiza a senha
    $stmtUpdate = $conn->prepare("UPDATE usuarios SET senha = ? WHERE id = ?");
    if (!$stmtUpdate) {
        $_SESSION['erro'] = "Erro ao preparar atualização de senha.";
        header("Location: " . URL_BASE . "login/redefinir_senha.php");
        exit;
    }

    $stmtUpdate->bind_param("si", $senha_hash, $usuario_id);
    if ($stmtUpdate->execute()) {
        $stmtUpdate->close();

        // 📌 Registra LOG de redefinição (se houver tabela `log_redefinicao_senha`)
        // Certifique-se de que a tabela `log_redefinicao_senha` existe e tem as colunas corretas.
        // Se não tiver, pode comentar ou adaptar esta parte.
        try {
            $ip = $_SERVER['REMOTE_ADDR'] ?? 'desconhecido';
            $navegador = substr($_SERVER['HTTP_USER_AGENT'] ?? 'não informado', 0, 250);
            $email_usuario_log = ""; // Obter o email do usuário para o log
            $stmt_email = $conn->prepare("SELECT email FROM usuarios WHERE id = ?");
            if ($stmt_email) {
                $stmt_email->bind_param("i", $usuario_id);
                $stmt_email->execute();
                $stmt_email->bind_result($email_usuario_log);
                $stmt_email->fetch();
                $stmt_email->close();
            }

            $stmtLog = $conn->prepare("INSERT INTO log_redefinicao_senha (usuario_id, email, ip, navegador) VALUES (?, ?, ?, ?)");
            if ($stmtLog) {
                $stmtLog->bind_param("isss", $usuario_id, $email_usuario_log, $ip, $navegador);
                $stmtLog->execute();
                $stmtLog->close();
            }
        } catch (Exception $e) {
            // Logar o erro, mas não impedir a redefinição de senha bem-sucedida
            error_log("Erro ao registrar log de redefinição de senha: " . $e->getMessage());
        }


        $_SESSION['sucesso'] = "Senha redefinida com sucesso. Faça login com sua nova senha.";
        header("Location: " . URL_BASE . "login/login.php");
        exit;
    } else {
        $stmtUpdate->close();
        $_SESSION['erro'] = "Erro ao redefinir a senha: " . $stmtUpdate->error;
        header("Location: " . URL_BASE . "login/nova_senha.php?token=" . urlencode($token));
        exit;
    }
} else {
    $_SESSION['erro'] = "Token inválido ou já utilizado.";
    header("Location: " . URL_BASE . "login/redefinir_senha.php");
    exit;
}

$conn->close();
?>