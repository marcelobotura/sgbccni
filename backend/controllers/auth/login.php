<?php
session_start();
require_once __DIR__ . '/../../../config/config.php';

// 🛡️ Resposta padrão
header('Content-Type: application/json');

// 📥 Dados do POST
$email = trim($_POST['email'] ?? '');
$senha = $_POST['senha'] ?? '';

// Validação básica
if (empty($email) || empty($senha)) {
    echo json_encode(['status' => 'erro', 'mensagem' => 'Preencha todos os campos.']);
    exit;
}

// Consulta no banco
$stmt = $conn->prepare("SELECT id, nome, senha, tipo FROM usuarios WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows === 1) {
    $stmt->bind_result($id, $nome, $senha_hash, $tipo);
    $stmt->fetch();

    if (password_verify($senha, $senha_hash)) {
        session_regenerate_id(true); // ✅ Segurança
        $_SESSION['usuario_id']   = $id;
        $_SESSION['usuario_nome'] = htmlspecialchars($nome);
        $_SESSION['usuario_tipo'] = $tipo;

        echo json_encode(['status' => 'sucesso', 'tipo' => $tipo]);
    } else {
        echo json_encode(['status' => 'erro', 'mensagem' => 'Senha incorreta.']);
    }
} else {
    echo json_encode(['status' => 'erro', 'mensagem' => 'E-mail não encontrado.']);
}

$stmt->close();
