<?php
session_start();
define('BASE_PATH', dirname(__DIR__, 2) . '/app_backend');
require_once BASE_PATH . '/config/config.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = trim($_POST['email'] ?? '');
    $senha = $_POST['senha'] ?? '';

    if (empty($email) || empty($senha)) {
        $_SESSION['erro'] = "Preencha todos os campos.";
        header("Location: index.php");
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
                header("Location: ../admin/listar_livros.php");
            } else {
                header("Location: ../usuario/index.php");
            }
            exit;
        } else {
            $_SESSION['erro'] = "Senha incorreta.";
        }
    } else {
        $_SESSION['erro'] = "Usuário não encontrado.";
    }

    header("Location: index.php");
    exit;
}
?>
