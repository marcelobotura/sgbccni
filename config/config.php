<?php
// ✅ Define a URL base uma única vez
if (!defined('URL_BASE')) {
// ✅ Evita redefinições
if (!defined('URL_BASE')) {
    define('URL_BASE', 'http://localhost/sgbccni/public_html/');
}
}

// ⚙️ Ambiente de desenvolvimento (altere para false em produção)
define('ENV_DEV', true);

// 🛡️ Configurações de erros e logs
ini_set('display_errors', ENV_DEV ? '1' : '0');
ini_set('log_errors', '1');
ini_set('error_log', __DIR__ . '/../logs/php-error.log');

// 🔐 Configurações de banco de dados
$host = 'localhost';
$usuario = 'root';
$senha = '';
$banco = 'sgbccni';

// 🔗 Conexão com o banco
$conn = new mysqli($host, $usuario, $senha, $banco);
$conn->set_charset('utf8');

// 🚨 Verifica erro de conexão
if ($conn->connect_error) {
    error_log("Erro de conexão: " . $conn->connect_error);
    die("Erro interno de conexão com o banco de dados.");
}

// ▶️ Inicia sessão, se necessário
if (session_status() === PHP_SESSION_NONE) {
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
}

// 🌐 Constantes do sistema
define('NOME_SISTEMA', 'Sistema de Gestão Biblioteca Comunitária - CNI');
define('VERSAO_SISTEMA', '1.0');
define('EMAIL_SUPORTE', 'mbsfoz@gmail.com');

// ⏰ Fuso horário
date_default_timezone_set('America/Sao_Paulo');
?>
