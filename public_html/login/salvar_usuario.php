<?php
define('BASE_PATH', dirname(__DIR__) . '/../app_backend');
require_once BASE_PATH . '/config/config.php';
require_once BASE_PATH . '/includes/session.php';

$nome = trim($_POST['nome']);
$email = trim($_POST['email']);
$senha = $_POST['senha'];

if (empty($nome) || empty($email) || empty($senha)) {
    $_SESSION['erro'] = 'Preencha todos os campos.';
    header('Location: register.php');
    exit;
}

$senha_hash = password_hash($senha, PASSWORD_DEFAULT);
$tipo = 'usuario'; // padrão

$stmt = $conn->prepare("INSERT INTO usuarios (nome, email, senha, tipo) VALUES (?, ?, ?, ?)");
$stmt->bind_param("ssss", $nome, $email, $senha_hash, $tipo);

if ($stmt->execute()) {
    $_SESSION['sucesso'] = 'Usuário cadastrado com sucesso!';
    header('Location: index.php');
} else {
    $_SESSION['erro'] = 'Erro ao cadastrar. Tente novamente.';
    header('Location: register.php');
}
exit;
