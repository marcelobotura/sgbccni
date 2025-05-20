<?php
define('URL_BASE', 'http://localhost/sgbccni/');
// ⚙️ Ambiente
define('ENV_DEV', true); // true: mostra erros; false: produção

// 🛡️ Controle de erros
ini_set('display_errors', ENV_DEV ? 1 : 0);
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/../logs/php-error.log');

// 🔐 Dados de conexão
$host = 'localhost';
$usuario = 'root';
$senha = '';
$banco = 'sgbccni';

// 🔗 Conexão com o banco
$conn = new mysqli($host, $usuario, $senha, $banco);
$conn->set_charset("utf8");

if ($conn->connect_error) {
    error_log("Erro de conexão: " . $conn->connect_error);
    die("Erro interno de conexão. Tente novamente mais tarde.");
}

// ▶️ Inicia sessão
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
