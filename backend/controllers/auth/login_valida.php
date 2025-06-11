<?php
session_start();

require_once __DIR__ . '/../../config/env.php';
require_once __DIR__ . '/../../config/config.php';

// 📥 Coleta segura dos dados do formulário
$email  = trim($_POST['email'] ?? '');
$senha  = $_POST['senha'] ?? '';
$origem = $_POST['origem'] ?? 'admin'; // Pode ser 'admin' ou 'usuario'

// 🔁 Página de login para redirecionamento em caso de erro
$loginPage = ($origem === 'usuario') ? '/frontend/login/login_user.php' : '/frontend/login/login_admin.php';

// 🧱 Validação básica
if (empty($email) || empty($senha)) {
    $_SESSION['erro'] = "Preencha todos os campos.";
    header('Location: ' . URL_BASE . $loginPage);
    exit;
}

try {
    // 📌 Usando PDO com prepared statement (já está ok aqui)
    $stmt = $conn->prepare("SELECT id, nome, senha, tipo FROM usuarios WHERE email = :email LIMIT 1");
    $stmt->bindParam(':email', $email);
    $stmt->execute();
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($usuario && password_verify($senha, $usuario['senha'])) {
        // 🔐 Login bem-sucedido
        $_SESSION['usuario_id']   = $usuario['id'];
        $_SESSION['usuario_nome'] = $usuario['nome'];
        $_SESSION['usuario_tipo'] = $usuario['tipo'];

        // Redireciona para área correta
        if ($usuario['tipo'] === 'admin') {
            header('Location: ' . URL_BASE . '/frontend/admin/pages/index.php');
        } else {
            header('Location: ' . URL_BASE . '/frontend/usuario/index.php');
        }
        exit;

    } else {
        // ❌ Erro de autenticação
        $_SESSION['erro'] = "E-mail ou senha incorretos.";
        header('Location: ' . URL_BASE . $loginPage);
        exit;
    }

} catch (PDOException $e) {
    // ❗ Logar erro em produção (não exibir)
    error_log("Erro ao fazer login: " . $e->getMessage());
    $_SESSION['erro'] = "Erro interno. Tente novamente.";
    header('Location: ' . URL_BASE . $loginPage);
    exit;
}
