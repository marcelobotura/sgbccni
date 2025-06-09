<?php
require_once __DIR__ . '/env.php';
require_once __DIR__ . '/../includes/session.php';

// 🔁 Define constantes globais
if (!defined('NOME_SISTEMA')) {
    define('NOME_SISTEMA', getenv('APP_NAME') ?: 'SGBCCNI');
    define('VERSAO_SISTEMA', getenv('APP_VERSION') ?: '2.6');
    define('EMAIL_SUPORTE', getenv('APP_EMAIL') ?: 'mbsfoz@gmail.com');
    define('ENV_DEV', getenv('ENV_DEV') === 'true');
}

// 🌐 Define URL base
if (!defined('URL_BASE')) {
    define('URL_BASE', getenv('URL_BASE') ?: '/sgbccni/');
}

// ⏰ Fuso horário
date_default_timezone_set(getenv('TIMEZONE') ?: 'America/Sao_Paulo');

// 📁 Diretório de logs
$logDir = dirname(__DIR__) . '/../logs';
if (!is_dir($logDir)) mkdir($logDir, 0777, true);

// 🐞 Configuração de erros
ini_set('display_errors', ENV_DEV ? '1' : '0');
ini_set('log_errors', '1');
ini_set('error_log', $logDir . '/php-error.log');

// ✅ Verifica se conexão foi definida corretamente
if (!isset($conn)) {
    die(json_encode(['status' => 'erro', 'mensagem' => 'Falha na conexão com o banco de dados.']));
}
