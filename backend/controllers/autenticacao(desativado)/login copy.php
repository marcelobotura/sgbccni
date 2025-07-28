<?php
// Caminho: backend/controllers/autenticacao/login.php

session_start();
require_once __DIR__ . '/../../config/env.php';
require_once __DIR__ . '/../../includes/session.php';
require_once __DIR__ . '/../../config/config.php'; // onde está definido $pdo

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim(filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL));
    $senha = $_POST['senha'];

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['erro'] = "E-mail inválido.";
        header("Location: " . URL_BASE . "frontend/login/login.php");
        exit;
    }

    try {
        $stmt = $pdo->prepare("SELECT id, nome, senha, tipo FROM usuarios WHERE email = ?");
        $stmt->execute([$email]);

        if ($usuario = $stmt->fetch()) {
            if (password_verify($senha, $usuario['senha'])) {
                session_regenerate_id(true);
                $_SESSION['usuario_id']   = $usuario['id'];
                $_SESSION['usuario_nome'] = htmlspecialchars($usuario['nome']);
                $_SESSION['usuario_tipo'] = $usuario['tipo'];

                // Redireciona conforme o tipo do usuário
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

        header("Location: " . URL_BASE . "frontend/login/login.php");
        exit;

    } catch (PDOException $e) {
        $_SESSION['erro'] = "Erro no login: " . $e->getMessage();
        header("Location: " . URL_BASE . "frontend/login/login.php");
        exit;
    }
}
