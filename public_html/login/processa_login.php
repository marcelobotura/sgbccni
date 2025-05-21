<?php
define('BASE_PATH', dirname(__DIR__) . '/../app_backend');
require_once BASE_PATH . '/config/config.php';
require_once BASE_PATH . '/includes/session.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['usuario']) ?? '';
    $senha = $_POST['senha'] ?? '';

    if (empty($email) || empty($senha)) {
        $_SESSION['erro'] = 'Preencha todos os campos.';
        header('Location: index.php');
        exit;
    }

    $stmt = $conn->prepare("SELECT id, nome, senha, tipo FROM usuarios WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado->num_rows === 1) {
        $usuario = $resultado->fetch_assoc();

        if (password_verify($senha, $usuario['senha'])) {
            $_SESSION['usuario_id'] = $usuario['id'];
            $_SESSION['usuario_nome'] = $usuario['nome'];
            $_SESSION['usuario_tipo'] = $usuario['tipo'];

            if ($usuario['tipo'] === 'admin') {
                header('Location: ' . URL_BASE . 'admin/');
            } else {
                header('Location: ' . URL_BASE . 'usuario/');
            }
            exit;
        } else {
            $_SESSION['erro'] = 'Senha incorreta.';
        }
    } else {
        $_SESSION['erro'] = 'Usuário não encontrado.';
    }

    header('Location: index.php');
    exit;
} else {
    header('Location: index.php');
    exit;
}
?>
