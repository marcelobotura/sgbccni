<?php
session_start();
define('BASE_PATH', dirname(__DIR__, 2)); // Vai até /backend
require_once BASE_PATH . '/config/config.php';
require_once BASE_PATH . '/includes/session.php';

// Validação simples
$nome = trim($_POST['nome'] ?? '');
$email = trim($_POST['email'] ?? '');
$mensagem = trim($_POST['mensagem'] ?? '');

if (empty($nome) || empty($email) || empty($mensagem)) {
    $_SESSION['erro'] = "Preencha todos os campos.";
    header("Location: " . URL_BASE . "public_html/contato.php");
    exit;
}

// Insere no banco
try {
    $stmt = $pdo->prepare("INSERT INTO mensagens (nome, email, mensagem, data_envio, lida) 
                           VALUES (:nome, :email, :mensagem, NOW(), 0)");
    $stmt->execute([
        ':nome' => $nome,
        ':email' => $email,
        ':mensagem' => $mensagem
    ]);

    $_SESSION['sucesso'] = "Mensagem enviada com sucesso!";
} catch (PDOException $e) {
    $_SESSION['erro'] = "Erro ao enviar mensagem: " . $e->getMessage();
}

header("Location: " . URL_BASE . "public_html/contato.php");
exit;
