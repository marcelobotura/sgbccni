<?php
session_start();
require_once __DIR__ . '/../../backend/config/config.php';

// ✅ Garante que a requisição seja POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: login_admin.php");
    exit;
}

$acao  = $_POST['acao'] ?? '';
$email = trim($_POST['email'] ?? '');
$senha = $_POST['senha'] ?? '';

// 🎯 Validação de ação
if ($acao !== 'login') {
    $_SESSION['erro'] = "Ação inválida.";
    header("Location: login_admin.php");
    exit;
}

// 🧪 Verifica campos obrigatórios
if (empty($email) || empty($senha)) {
    $_SESSION['erro'] = "Preencha todos os campos.";
    header("Location: login_admin.php");
    exit;
}

// 🔍 Busca o usuário pelo e-mail
$stmt = $conn->prepare("SELECT id, nome, senha, tipo FROM usuarios WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows === 1) {
    $stmt->bind_result($id, $nome, $senha_hash, $tipo);
    $stmt->fetch();

    // 🔐 Verifica a senha
    if (password_verify($senha, $senha_hash)) {
        $_SESSION['usuario_id']   = $id;
        $_SESSION['usuario_nome'] = $nome;
        $_SESSION['usuario_tipo'] = $tipo;

        // 🔀 Redireciona conforme tipo
        if ($tipo === 'admin') {
            header("Location: ../../frontend/admin/dashboard.php");
        } else {
            header("Location: ../../frontend/usuario/index.php");
        }
        exit;
    } else {
        $_SESSION['erro'] = "Senha incorreta.";
    }
} else {
    $_SESSION['erro'] = "E-mail não encontrado.";
}

$stmt->close();
header("Location: login_admin.php");
exit;
