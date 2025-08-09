<?php
// Caminho: public_html/autenticacao.php
ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/../backend/config/config.php';
require_once __DIR__ . '/../backend/includes/session.php';
require_once __DIR__ . '/../backend/includes/db.php'; // $pdo disponível

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: " . URL_BASE . "login.php");
    exit;
}

$email = trim(filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL));
$senha = $_POST['senha'] ?? '';

if (!$email || !$senha) {
    $_SESSION['erro'] = 'E-mail e senha são obrigatórios.';
    header("Location: " . URL_BASE . "login.php");
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $_SESSION['erro'] = 'E-mail inválido.';
    header("Location: " . URL_BASE . "login.php");
    exit;
}

try {
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
    }

    $_SESSION['erro'] = 'E-mail ou senha inválidos.';
} catch (PDOException $e) {
    $_SESSION['erro'] = 'Erro ao conectar com o banco de dados.';
}

// Falhou → volta ao login
header("Location: " . URL_BASE . "login.php");
exit;
