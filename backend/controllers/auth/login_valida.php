<?php
session_start();
require_once __DIR__ . '/../../config/config.php';

header('Content-Type: application/json');

// ✅ Verifica se a requisição é POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode([
        'status' => 'erro',
        'mensagem' => 'Requisição inválida.'
    ]);
    exit;
}

$email = trim($_POST['email'] ?? '');
$senha = $_POST['senha'] ?? '';
$acao  = $_POST['acao'] ?? 'login';

// 🎯 Verifica ação e campos obrigatórios
if ($acao !== 'login' || empty($email) || empty($senha)) {
    echo json_encode([
        'status' => 'erro',
        'mensagem' => 'Preencha todos os campos corretamente.'
    ]);
    exit;
}

// 🔍 Busca usuário por e-mail
$stmt = $conn->prepare("SELECT id, nome, senha, tipo FROM usuarios WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows === 1) {
    $stmt->bind_result($id, $nome, $senha_hash, $tipo);
    $stmt->fetch();

    if (password_verify($senha, $senha_hash)) {
        // 🔐 Define variáveis de sessão
        $_SESSION['usuario_id']   = $id;
        $_SESSION['usuario_nome'] = $nome;
        $_SESSION['usuario_tipo'] = $tipo;

        echo json_encode([
            'status' => 'sucesso',
            'mensagem' => 'Login realizado com sucesso!',
            'tipo' => $tipo
        ]);
    } else {
        echo json_encode([
            'status' => 'erro',
            'mensagem' => 'Senha incorreta.'
        ]);
    }
} else {
    echo json_encode([
        'status' => 'erro',
        'mensagem' => 'E-mail não encontrado.'
    ]);
}

$stmt->close();
exit;
