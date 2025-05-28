<?php
session_start();
require_once __DIR__ . '/../backend/config/config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome     = trim($_POST['nome'] ?? '');
    $email    = trim($_POST['email'] ?? '');
    $mensagem = trim($_POST['mensagem'] ?? '');

    // 🔒 Validação dos campos
    if (empty($nome) || empty($email) || empty($mensagem)) {
        $_SESSION['erro'] = "Preencha todos os campos.";
        header("Location: contato.php", true, 302);
        exit;
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['erro'] = "E-mail inválido.";
        header("Location: contato.php", true, 302);
        exit;
    }

    // 💾 Inserção no banco de dados
    try {
        $stmt = $conn->prepare("INSERT INTO mensagens_contato (nome, email, mensagem) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $nome, $email, $mensagem);

        if ($stmt->execute()) {
            $_SESSION['sucesso'] = "✅ Mensagem enviada com sucesso! Agradecemos o contato.";
        } else {
            $_SESSION['erro'] = "❌ Erro ao salvar mensagem. Tente novamente.";
        }

        $stmt->close();
    } catch (Exception $e) {
        $_SESSION['erro'] = "❌ Erro ao processar a mensagem.";
        error_log("Erro ao salvar contato: " . $e->getMessage());
    }

    header("Location: contato.php", true, 302);
    exit;
}

// 🚫 Acesso direto sem POST
$_SESSION['erro'] = "Acesso inválido.";
header("Location: contato.php", true, 302);
exit;
