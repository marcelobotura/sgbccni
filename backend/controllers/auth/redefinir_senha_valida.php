<?php
session_start();

require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../config/env.php';
require_once __DIR__ . '/../../includes/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');

    if (empty($email)) {
        $_SESSION['erro'] = "Informe seu e-mail.";
        header("Location: " . URL_BASE . "frontend/login/redefinir_senha.php");
        exit;
    }

    try {
        // Verifica se o usuário existe
        $stmt = $pdo->prepare("SELECT id, nome, tipo FROM usuarios WHERE email = ?");
        $stmt->execute([$email]);
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$usuario) {
            $_SESSION['erro'] = "E-mail não encontrado.";
            header("Location: " . URL_BASE . "frontend/login/redefinir_senha.php");
            exit;
        }

        // Gera token único
        $token = bin2hex(random_bytes(32));
        $expira_em = date('Y-m-d H:i:s', strtotime('+1 hour'));

        // Salva token no banco
        $stmt = $pdo->prepare("INSERT INTO tokens_recuperacao (usuario_id, token, expira_em) VALUES (?, ?, ?)");
        $stmt->execute([$usuario['id'], $token, $expira_em]);

        // Monta link de redefinição
        $link = URL_BASE . "frontend/login/nova_senha.php?token=" . urlencode($token);

        // Simula envio de e-mail (substitua por PHPMailer ou função real depois)
        $mensagem = "Olá {$usuario['nome']},\n\n";
        $mensagem .= "Clique no link abaixo para redefinir sua senha:\n$link\n\n";
        $mensagem .= "Este link expira em 1 hora.";

        // ✅ Apenas simulação:
        file_put_contents(__DIR__ . '/../../logs/email-simulacao.txt', $mensagem);

        $_SESSION['sucesso'] = "Um link de redefinição foi enviado para seu e-mail.";
        header("Location: " . URL_BASE . "frontend/login/redefinir_senha.php");
        exit;

    } catch (PDOException $e) {
        $_SESSION['erro'] = "Erro ao gerar o link: " . $e->getMessage();
        header("Location: " . URL_BASE . "frontend/login/redefinir_senha.php");
        exit;
    }

} else {
    header("Location: " . URL_BASE . "frontend/login/redefinir_senha.php");
    exit;
}
