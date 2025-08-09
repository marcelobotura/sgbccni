<?php
// backend/config/env.php

// 🌐 URL base (normalizada com / no final)
$envUrlBase = getenv('URL_BASE') ?: 'http://localhost/sgbccni/';
$envUrlBase = rtrim($envUrlBase, '/') . '/';
if (!defined('URL_BASE')) {
    define('URL_BASE', $envUrlBase);
}

// ⚙️ Ambiente
$envDev = getenv('ENV_DEV');
define('ENV_DEV', ($envDev === false) ? true : ($envDev !== 'false'));

// ⏰ Fuso
date_default_timezone_set(getenv('TIMEZONE') ?: 'America/Sao_Paulo');

// 📌 Configs
define('APP_NAME', getenv('APP_NAME') ?: 'SGBCCNI');
define('APP_ENV', getenv('APP_ENV') ?: 'local');
define('DB_HOST', getenv('DB_HOST') ?: 'localhost');
define('DB_NAME', getenv('DB_NAME') ?: 'sgbccni');
define('DB_USER', getenv('DB_USER') ?: 'root');
define('DB_PASS', getenv('DB_PASS') ?: '');

// Aliases públicos
define('NOME_SISTEMA', APP_NAME);
define('VERSAO_SISTEMA', getenv('APP_VERSION') ?: '1.0');
define('EMAIL_SUPORTE', getenv('APP_EMAIL') ?: 'mbsfoz@gmail.com');

// 🗂️ Logs (com fallback seguro)
$rootDir = dirname(__DIR__, 2);             // → raiz do projeto
$logDir  = $rootDir . DIRECTORY_SEPARATOR . 'storage' . DIRECTORY_SEPARATOR . 'logs';

if (!is_dir($logDir)) {
    @mkdir($logDir, 0775, true);
    if (!is_dir($logDir)) {
        $logDir = rtrim(sys_get_temp_dir(), DIRECTORY_SEPARATOR);
    }
}

// ⚠️ Erros
ini_set('display_errors', ENV_DEV ? '1' : '0');
ini_set('log_errors', '1');
ini_set('error_log', $logDir . DIRECTORY_SEPARATOR . 'php-error.log');
