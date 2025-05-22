<?php
session_start();
define('BASE_PATH', dirname(__DIR__, 2) . '/app_backend');
require_once BASE_PATH . '/config/config.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nome = trim($_POST['nome'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $senha = $_POST['senha'] ?? '';

    if (empty($nome) || empty($email) || empty($senha)) {
        $_SESSION['erro'] = "Preencha todos os campos.";
        header("Location: register.php");
        exit;
    }

    // Verifica se o e-mail já está cadastrado
    $stmt = $conn->prepare("SELECT id FROM usuarios WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $_SESSION['erro'] = "Já existe uma conta com este e-mail.";
        header("Location: register.php");
        exit;
    }

    $senha_hash = password_hash($senha, PASSWORD_DEFAULT);
    $tipo = 'usuario';

    $stmt = $conn->prepare("INSERT INTO usuarios (nome, email, senha, tipo) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $nome, $email, $senha_hash, $tipo);

    if ($stmt->execute()) {
        $_SESSION['sucesso'] = "✅ Conta criada com sucesso. Faça login.";
        header("Location: register.php");
        exit;
    } else {
        $_SESSION['erro'] = "Erro ao registrar: " . $stmt->error;
        header("Location: register.php");
        exit;
    }
}
?>
