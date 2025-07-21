<?php
session_start();
require_once __DIR__ . '/../backend/config/config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Captura dos dados do formulário
    $nome     = trim($_POST['nome'] ?? '');
    $email    = trim($_POST['email'] ?? '');
    $celular  = trim($_POST['celular'] ?? '');
    $assunto  = trim($_POST['assunto'] ?? '');
    $mensagem = trim($_POST['mensagem'] ?? '');

    // Salva os dados em sessão para reaproveitar se der erro
    $_SESSION['form_data'] = [
        'nome'     => $nome,
        'email'    => $email,
        'celular'  => $celular,
        'assunto'  => $assunto,
        'mensagem' => $mensagem
    ];

    // Validação
    if (empty($nome) || empty($email) || empty($mensagem)) {
        $_SESSION['erro'] = "Preencha todos os campos obrigatórios.";
        header("Location: contato.php");
        exit;
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['erro'] = "E-mail inválido.";
        header("Location: contato.php");
        exit;
    }

    try {
        // Insere os dados no banco
        $stmt = $pdo->prepare("INSERT INTO mensagens (nome, email, celular, assunto, mensagem) VALUES (?, ?, ?, ?, ?)");
        $resultado = $stmt->execute([$nome, $email, $celular, $assunto, $mensagem]);

        if ($resultado) {
            $_SESSION['sucesso'] = "✅ Mensagem enviada com sucesso!";
            unset($_SESSION['form_data']);
        } else {
            $_SESSION['erro'] = "❌ Erro ao salvar mensagem.";
        }
    } catch (PDOException $e) {
        $_SESSION['erro'] = "❌ Erro ao processar a mensagem.";
        error_log("Erro ao salvar mensagem: " . $e->getMessage());
    }

    header("Location: contato.php");
    exit;
}

$_SESSION['erro'] = "Acesso inválido.";
header("Location: contato.php");
exit;
