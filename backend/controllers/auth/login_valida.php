<?php
// Exibe erros durante o desenvolvimento
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Inicia sessão
session_start();

// Carrega as configurações e a conexão com o banco
require_once '../../config/env.php';
require_once '../../config/config.php';
require_once '../../includes/atividade_logger.php';



// Captura os dados do formulário
$email   = trim($_POST['email'] ?? '');
$senha   = trim($_POST['senha'] ?? '');
$origem  = $_POST['origem'] ?? 'usuario'; // padrão

// Define página de login conforme a origem
$loginPage = ($origem === 'admin') ? '/frontend/admin/pages/login_admin.php' : '/frontend/login/login_user.php';

// Validação básica
if (empty($email) || empty($senha)) {
    header('Location: ' . URL_BASE . $loginPage . '?erro=1');
    exit;
}

try {
    // Busca usuário pelo e-mail
    $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE email = :email LIMIT 1");
    $stmt->bindParam(':email', $email);
    $stmt->execute();
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

    // Verifica existência e senha correta
    if ($usuario && password_verify($senha, $usuario['senha'])) {

        // Impede acesso cruzado
        if (
            ($origem === 'admin' && $usuario['tipo'] !== 'admin') ||
            ($origem === 'usuario' && $usuario['tipo'] === 'admin')
        ) {
            header('Location: ' . URL_BASE . $loginPage . '?erro=1');
            exit;
        }

        // Salva sessão
        $_SESSION['usuario_id']   = $usuario['id'];
        $_SESSION['usuario_nome'] = $usuario['nome'];
        $_SESSION['usuario_tipo'] = $usuario['tipo'];

        // 🔐 Registra o log de login
        $logStmt = $pdo->prepare("INSERT INTO log_login (usuario_id, ip, navegador) VALUES (:usuario_id, :ip, :navegador)");
        $logStmt->execute([
            ':usuario_id' => $usuario['id'],
            ':ip'         => $_SERVER['REMOTE_ADDR'],
            ':navegador'  => $_SERVER['HTTP_USER_AGENT']
        ]);

        // Redireciona para painel
        if ($usuario['tipo'] === 'admin') {
            header('Location: ' . URL_BASE . '/frontend/admin/index.php');
        } else {
            header('Location: ' . URL_BASE . '/frontend/usuario/index.php');
        }
        exit;

    } else {
        // Login inválido
        header('Location: ' . URL_BASE . $loginPage . '?erro=1');
        exit;
    }

} catch (PDOException $e) {
    echo 'Erro no login: ' . htmlspecialchars($e->getMessage());
    exit;
}
