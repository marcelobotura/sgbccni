<?php
// backend/config/env.php

// 🌐 Define a URL base global
if (!defined('URL_BASE')) {
   define('URL_BASE', getenv('URL_BASE') ?: 'http://localhost/sgbccni/');

}

// ⚙️ Ambiente: true = desenvolvimento, false = produção
define('ENV_DEV', getenv('ENV_DEV') === 'false');

// ⏰ Fuso horário
date_default_timezone_set(getenv('TIMEZONE') ?: 'America/Sao_Paulo');

// 📌 Variáveis de conexão com banco (para uso no config.php)
define('DB_HOST', getenv('DB_HOST') ?: 'localhost');
define('DB_NAME', getenv('DB_NAME') ?: 'sgbccni');
define('DB_USER', getenv('DB_USER') ?: 'root');
define('DB_PASS', getenv('DB_PASS') ?: '');

// 📌 Informações globais do sistema
define('NOME_SISTEMA', getenv('APP_NAME') ?: 'SGBCCNI');
define('VERSAO_SISTEMA', getenv('APP_VERSION') ?: '1.0');
define('EMAIL_SUPORTE', getenv('APP_EMAIL') ?: 'mbsfoz@gmail.com');

// 🗂️ Diretório de logs
$logDir = dirname(__DIR__, 2) . '/storage/logs';
if (!is_dir($logDir)) {
    mkdir($logDir, 0775, true); // ou 0755 se preferir mais seguro
}

// ⚠️ Logs e exibição de erros
ini_set('display_errors', ENV_DEV ? '1' : '0');
ini_set('log_errors', '1');
ini_set('error_log', $logDir . '/php-error.log');
