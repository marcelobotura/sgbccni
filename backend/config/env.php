<?php
// 📦 Carrega variáveis do arquivo .env
require_once __DIR__ . '/env.php'; // Certifique-se de que env.php está presente no mesmo diretório

// 🌐 Define URL base se ainda não estiver definida
if (!defined('URL_BASE')) {
    define('URL_BASE', getenv('URL_BASE') ?: 'http://localhost/sgbccni/public_html/');
}

// ⚙️ Define o ambiente (true = dev | false = produção)
define('ENV_DEV', getenv('ENV_DEV') === 'true');

// ⏰ Fuso horário (personalizável pelo .env)
date_default_timezone_set(getenv('TIMEZONE') ?: 'America/Sao_Paulo');

// 📁 Diretório de logs
$logDir = __DIR__ . '/../logs';
if (!is_dir($logDir)) {
    mkdir($logDir, 0777, true);
}

// 🔧 Configurações de erro
ini_set('display_errors', ENV_DEV ? '1' : '0');
ini_set('log_errors', '1');
ini_set('error_log', $logDir . '/php-error.log');

// 🔌 Dados de conexão do banco via .env
$host    = getenv('DB_HOST');
$usuario = getenv('DB_USER');
$senha   = getenv('DB_PASS');
$banco   = getenv('DB_NAME');

// 🔐 Conexão segura usando mysqli com tratamento de erros
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

try {
    $conn = new mysqli($host, $usuario, $senha, $banco);
    $conn->set_charset("utf8mb4");
} catch (mysqli_sql_exception $e) {
    error_log("Erro ao conectar: " . $e->getMessage());
    die(ENV_DEV ? "Erro ao conectar: " . $e->getMessage() : "Erro ao conectar ao banco de dados.");
}

// 📌 Informações do sistema
define('NOME_SISTEMA', getenv('APP_NAME') ?: 'Sistema Biblioteca CNI');
define('VERSAO_SISTEMA', getenv('APP_VERSION') ?: '1.0');
define('EMAIL_SUPORTE', getenv('APP_EMAIL') ?: 'suporte@exemplo.com');
