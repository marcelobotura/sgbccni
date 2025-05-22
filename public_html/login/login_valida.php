<?php
session_start();
require_once __DIR__ . '/../../config/config.php';

$email = trim($_POST['usuario'] ?? '');
$senha = $_POST['senha'] ?? '';

if (empty($email) || empty($senha)) {
    $_SESSION['erro'] = 'Preencha todos os campos.';
    header('Location: index.php');
    exit;
}

$stmt = $conn->prepare("SELECT id, nome, senha, tipo FROM usuarios WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows === 1) {
    $stmt->bind_result($id, $nome, $senha_hash, $tipo);
    $stmt->fetch();

    if (password_verify($senha, $senha_hash)) {
        $_SESSION['usuario_id'] = $id;
        $_SESSION['usuario_nome'] = $nome;
        $_SESSION['usuario_tipo'] = $tipo;

        if ($tipo === 'admin') {
            header("Location: ../admin/index.php");
        } else {
            header("Location: ../usuario/index.php");
        }
        exit;
    }
}

$_SESSION['erro'] = 'E-mail ou senha inválidos.';
header('Location: index.php');
exit;
