<?php
// 📦 Carrega variáveis do .env via env.php
require_once __DIR__ . '/env.php';

// 🌐 Define URL base global (caso não exista)
if (!defined('URL_BASE')) {
    define('URL_BASE', getenv('URL_BASE') ?: 'http://localhost/sgbccni/public_html/');
}

// ⚙️ Ambiente: Desenvolvimento (true) ou Produção (false)
define('ENV_DEV', getenv('ENV_DEV') === 'true');

// ⏰ Fuso horário padrão
date_default_timezone_set(getenv('TIMEZONE') ?: 'America/Sao_Paulo');

// 📁 Cria diretório de logs se necessário
$logDir = dirname(__DIR__) . '/logs';
if (!is_dir($logDir)) {
    mkdir($logDir, 0777, true);
}

// ⚠️ Log de erros
ini_set('display_errors', ENV_DEV ? '1' : '0');
ini_set('log_errors', '1');
ini_set('error_log', $logDir . '/php-error.log');

// 🔌 Conexão com banco via variáveis de ambiente
$host    = getenv('DB_HOST') ?: 'localhost';
$usuario = getenv('DB_USER') ?: 'root';
$senha   = getenv('DB_PASS') ?: '';
$banco   = getenv('DB_NAME') ?: 'sgbccni'; // valor default apenas como fallback

// ✅ Conexão segura com tratamento de exceção
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

try {
    $conn = new mysqli($host, $usuario, $senha, $banco);
    $conn->set_charset("utf8mb4");
} catch (mysqli_sql_exception $e) {
    error_log("Erro ao conectar: " . $e->getMessage());
    die(ENV_DEV ? "Erro ao conectar: " . $e->getMessage() : "Erro ao conectar ao banco de dados.");
}

// 📌 Informações globais do sistema
define('NOME_SISTEMA', getenv('APP_NAME') ?: 'SGBCCNI');
define('VERSAO_SISTEMA', getenv('APP_VERSION') ?: '2.6');
define('EMAIL_SUPORTE', getenv('APP_EMAIL') ?: 'mbsfoz@gmail.com');
