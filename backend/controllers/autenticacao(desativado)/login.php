<?php
// Garante que BASE_PATH seja definida
if (!defined('BASE_PATH')) {
    define('BASE_PATH', realpath(__DIR__ . '/../../..'));
}
require_once BASE_PATH . '/backend/config/config.php';
session_start();
// Processamento do login via formulário
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim(filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL));
    $senha = $_POST['senha'] ?? '';

    // Validação de e-mail
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['erro'] = "E-mail inválido.";
        header("Location: " . URL_BASE . "frontend/login/login.php");
        exit;
    }

    try {
        // Conexão com o banco
        $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASS);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Busca usuário
        $stmt = $pdo->prepare("SELECT id, nome, senha, tipo FROM usuarios WHERE email = ?");
        $stmt->execute([$email]);

        if ($usuario = $stmt->fetch()) {
            if (password_verify($senha, $usuario['senha'])) {
                session_regenerate_id(true); // Evita fixation
                $_SESSION['usuario_id']   = $usuario['id'];
                $_SESSION['usuario_nome'] = htmlspecialchars($usuario['nome']);
                $_SESSION['usuario_tipo'] = $usuario['tipo'];

                // Redireciona conforme tipo
                switch ($usuario['tipo']) {
                    case 'master':
                    case 'admin':
                        header("Location: " . URL_BASE . "frontend/admin/index.php");
                        break;
                    case 'usuario':
                    default:
                        header("Location: " . URL_BASE . "frontend/usuario/index.php");
                        break;
                }
                exit;
            } else {
                $_SESSION['erro'] = "Senha incorreta.";
            }
        } else {
            $_SESSION['erro'] = "E-mail não encontrado.";
        }
    } catch (PDOException $e) {
        $_SESSION['erro'] = "Erro no login: " . $e->getMessage();
    }

    // Redirecionamento em caso de falha
    header("Location: " . URL_BASE . "frontend/login/login.php");
    exit;
}
