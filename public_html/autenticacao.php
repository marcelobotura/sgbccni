<?php
// Caminho: public_html/autenticacao.php

ini_set('display_errors', 1);
error_reporting(E_ALL);

// Carrega configurações e dependências
require_once __DIR__ . '/../backend/config/config.php';
require_once __DIR__ . '/../backend/includes/db.php';
require_once __DIR__ . '/../backend/includes/session.php'; // já inicia sessão

// ✅ Verifica se o método da requisição é POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: " . URL_BASE . "login.php");
    exit;
}

// ✅ Coleta e sanitiza os dados do formulário
$email = trim($_POST['email'] ?? '');
$senha = $_POST['senha'] ?? '';

// ✅ Verifica campos obrigatórios
if (empty($email) || empty($senha)) {
    $_SESSION['erro'] = 'E-mail e senha são obrigatórios.';
    header("Location: " . URL_BASE . "login.php");
    exit;
}

// ✅ Conecta ao banco e busca o usuário
try {
    $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stmt = $pdo->prepare("SELECT id, nome, email, senha, tipo FROM usuarios WHERE email = :email LIMIT 1");
    $stmt->execute(['email' => $email]);
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($usuario && password_verify($senha, $usuario['senha'])) {
        session_regenerate_id(true);

        $_SESSION['usuario_id']    = $usuario['id'];
        $_SESSION['usuario_nome']  = $usuario['nome'];
        $_SESSION['usuario_email'] = $usuario['email'];
        $_SESSION['usuario_tipo']  = $usuario['tipo'];

        $destino = 'frontend/usuario/index.php';
        if (in_array($usuario['tipo'], ['admin', 'master'])) {
            $destino = 'frontend/admin/index.php';
        }

        header("Location: " . URL_BASE . $destino);
        exit;
    } else {
        $_SESSION['erro'] = 'E-mail ou senha inválidos.';
    }
} catch (PDOException $e) {
    $_SESSION['erro'] = 'Erro ao conectar com o banco de dados.';
}

// ⚠️ Se chegou aqui, houve falha no login
header("Location: " . URL_BASE . "login.php");
exit;
