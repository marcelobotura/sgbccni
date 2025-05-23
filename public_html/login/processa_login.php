<?php
session_start();
require_once __DIR__ . '/../../backend/config/config.php';

// Verifica se o formulário foi enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['usuario'] ?? '');
    $senha = $_POST['senha'] ?? '';

    // Validação básica
    if (empty($email) || empty($senha)) {
        $_SESSION['erro'] = 'Preencha todos os campos.';
        header("Location: " . URL_BASE . "login/index.php");
        exit;
    }

    // Busca o usuário
    $stmt = $conn->prepare("SELECT id, nome, senha, tipo FROM usuarios WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows === 1) {
        $stmt->bind_result($id, $nome, $senha_hash, $tipo);
        $stmt->fetch();

        if (password_verify($senha, $senha_hash)) {
            $_SESSION['usuario_id']   = $id;
            $_SESSION['usuario_nome'] = $nome;
            $_SESSION['usuario_tipo'] = $tipo;

            // Redireciona de acordo com o tipo
            if ($tipo === 'admin') {
                $_SESSION['admin_logado'] = true;
                header("Location: " . URL_BASE . "admin/index.php");
            } else {
                header("Location: " . URL_BASE . "usuario/index.php");
            }
            exit;
        } else {
            $_SESSION['erro'] = 'Senha incorreta.';
        }
    } else {
        $_SESSION['erro'] = 'Usuário não encontrado.';
    }

    $stmt->close();
    header("Location: " . URL_BASE . "login/index.php");
    exit;
} else {
    // Acesso direto ao script sem POST
    $_SESSION['erro'] = 'Acesso inválido.';
    header("Location: " . URL_BASE . "login/index.php");
    exit;
}
