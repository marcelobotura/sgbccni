<?php
// Carrega variáveis do .env
require_once __DIR__ . '/env.php';

// Define URL base
define('URL_BASE', getenv('URL_BASE') ?: 'http://localhost/');

// Ambiente de desenvolvimento
define('ENV_DEV', true);

// ⚠️ Exibição de erros (ativado se ENV_DEV = true)
ini_set('display_errors', ENV_DEV ? '1' : '0');
ini_set('display_startup_errors', ENV_DEV ? '1' : '0');
error_reporting(ENV_DEV ? E_ALL : 0);

// Log de erros
ini_set('log_errors', '1');
ini_set('error_log', __DIR__ . '/../logs/php-error.log');

// Variáveis de conexão
$host = getenv('DB_HOST') ?: 'localhost';
$usuario = getenv('DB_USER') ?: 'root';
$senha = getenv('DB_PASS') ?: '';
$banco = getenv('DB_NAME') ?: 'sgbccni';

// Conexão com MySQL
$conn = new mysqli($host, $usuario, $senha, $banco);
$conn->set_charset("utf8");

// Verifica erro na conexão
if ($conn->connect_error) {
    error_log("Erro de conexão: " . $conn->connect_error);
    die("<h3>❌ Erro ao conectar ao banco de dados. Verifique config.php e .env</h3>");
}

// Função para exibir mensagens da sessão
function exibir_mensagens_sessao() {
    // Inicia a sessão se ainda não estiver iniciada
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    if (isset($_SESSION['sucesso'])) {
        echo '<div class="alert alert-success mt-3">' . htmlspecialchars($_SESSION['sucesso']) . '</div>';
        unset($_SESSION['sucesso']); // Limpa a mensagem após exibição
    }
    if (isset($_SESSION['erro'])) {
        echo '<div class="alert alert-danger mt-3">' . htmlspecialchars($_SESSION['erro']) . '</div>';
        unset($_SESSION['erro']); // Limpa a mensagem após exibição
    }
}
?>