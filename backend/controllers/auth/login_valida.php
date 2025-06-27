<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
require_once '../../config/env.php';
require_once '../../config/config.php';

define('URL_BASE', '/sgbccni');

$email = $_POST['email'] ?? '';
$senha = $_POST['senha'] ?? '';
$origem = $_POST['origem'] ?? 'usuario'; // Valor padrão agora é 'usuario'

// Decide qual página de login mostrar em caso de erro
$loginPage = ($origem === 'usuario') ? '/frontend/login/login_user.php' : '/frontend/login/login_admin.php';

if (empty($email) || empty($senha)) {
    header('Location: ' . URL_BASE . $loginPage . '?erro=1');
    exit;
}

try {
    $stmt = $conn->prepare("SELECT * FROM usuarios WHERE email = :email LIMIT 1");
    $stmt->bindParam(':email', $email);
    $stmt->execute();
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($usuario && password_verify($senha, $usuario['senha'])) {
        // Impede cruzamento: só permite admin no login_admin e só usuario no login_user
        if (($origem === 'admin' && $usuario['tipo'] !== 'admin') || ($origem === 'usuario' && $usuario['tipo'] === 'admin')) {
            header('Location: ' . URL_BASE . $loginPage . '?erro=1');
            exit;
        }
        $_SESSION['usuario_id'] = $usuario['id'];
        $_SESSION['usuario_nome'] = $usuario['nome'];
        $_SESSION['usuario_tipo'] = $usuario['tipo'];

        if ($usuario['tipo'] === 'admin') {
            header('Location: ' . URL_BASE . '/frontend/admin/pages/index.php');
        } else {
            header('Location: ' . URL_BASE . '/frontend/usuario/index.php');
        }
        exit;
    } else {
        header('Location: ' . URL_BASE . $loginPage . '?erro=1');
        exit;
    }
} catch (PDOException $e) {
    echo 'Erro no login: ' . $e->getMessage();
}
