<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
require_once __DIR__ . '/../../config/config.php';

// Define tipo de resposta JSON
header('Content-Type: application/json');

// Sanitização
$email = trim($_POST['email'] ?? '');
$senha = $_POST['senha'] ?? '';

// Validação simples
if (!filter_var($email, FILTER_VALIDATE_EMAIL) || empty($senha)) {
    echo json_encode(['status' => 'erro', 'mensagem' => 'E-mail ou senha inválidos']);
    exit;
}

// Consulta o banco
$stmt = $conn->prepare("SELECT id, nome, senha, tipo FROM usuarios WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($usuario = $result->fetch_assoc()) {
    if (password_verify($senha, $usuario['senha'])) {
        // Login bem-sucedido
        session_regenerate_id(true);
        $_SESSION['usuario_id'] = $usuario['id'];
        $_SESSION['usuario_nome'] = $usuario['nome'];
        $_SESSION['usuario_tipo'] = $usuario['tipo'];

        echo json_encode([
            'status' => 'sucesso',
            'mensagem' => 'Login realizado!',
            'tipo' => $usuario['tipo']
        ]);
    } else {
        echo json_encode(['status' => 'erro', 'mensagem' => 'Senha incorreta.']);
    }
} else {
    echo json_encode(['status' => 'erro', 'mensagem' => 'Usuário não encontrado.']);
}
