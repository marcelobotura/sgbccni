<?php
require_once '../../backend/config/config.php';
require_once '../../backend/includes/db.php';

session_start();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nome = trim($_POST['nome']);
    $email = trim($_POST['email']);
    $senha = trim($_POST['senha']);
    $senha_hash = password_hash($senha, PASSWORD_DEFAULT);
    
    // Sempre define como usuário comum
    $tipo = 'usuario';

    // Validação simples
    if (empty($nome) || empty($email) || empty($senha)) {
        $_SESSION['erro'] = "Preencha todos os campos.";
        header("Location: ../login/register_user.php");
        exit;
    }

    try {
        // Verifica se e-mail já está em uso
        $stmt = $conn->prepare("SELECT id FROM usuarios WHERE email = ?");
        $stmt->execute([$email]);

        if ($stmt->rowCount() > 0) {
            $_SESSION['erro'] = "E-mail já cadastrado.";
            header("Location: ../login/register_user.php");
            exit;
        }

        // Insere novo usuário
        $stmt = $conn->prepare("INSERT INTO usuarios (nome, email, senha, tipo) VALUES (?, ?, ?, ?)");
        $stmt->execute([$nome, $email, $senha_hash, $tipo]);

        $_SESSION['sucesso'] = "Cadastro realizado com sucesso! Faça login.";

        // ✅ Redirecionamento automático com base no tipo de usuário
        if ($tipo === 'admin') {
            header("Location: ../login/login_admin.php");
        } else {
            header("Location: ../login/login_user.php");
        }
        exit;

    } catch (PDOException $e) {
        $_SESSION['erro'] = "Erro no cadastro: " . $e->getMessage();
        header("Location: ../login/register_user.php");
        exit;
    }
} else {
    header("Location: ../login/register_user.php");
    exit;
}
