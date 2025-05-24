<?php
// 🌐 Define a URL base apenas se ainda não estiver definida
if (!defined('URL_BASE')) {
    define('URL_BASE', 'http://localhost/sgbccni/public_html/'); // 🛠️ Altere para seu domínio em produção
}

// ⚙️ Ambiente: true = desenvolvimento | false = produção
define('ENV_DEV', true);

// ⏰ Define o fuso horário padrão
date_default_timezone_set('America/Sao_Paulo');

// 📁 Diretório de logs (cria se não existir)
$logDir = __DIR__ . '/../logs';
if (!is_dir($logDir)) {
    mkdir($logDir, 0777, true);
}

// 🔧 Configuração de exibição e log de erros
ini_set('display_errors', ENV_DEV ? '1' : '0');
ini_set('log_errors', '1');
ini_set('error_log', $logDir . '/php-error.log');

// 🔐 Dados sensíveis via env.php
$env = require_once __DIR__ . '/env.php';
$host    = $env['host'];
$usuario = $env['usuario'];
$senha   = $env['senha'];
$banco   = $env['banco'];

// 🔌 Conexão com o banco usando mysqli com tratamento de erros
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

try {
    $conn = new mysqli($host, $usuario, $senha, $banco);
    $conn->set_charset("utf8mb4");
} catch (mysqli_sql_exception $e) {
    error_log("Erro ao conectar: " . $e->getMessage());
    if (ENV_DEV) {
        die("Erro ao conectar: " . $e->getMessage());
    } else {
        die("Erro ao conectar ao banco de dados. Tente novamente mais tarde.");
    }
}

// 📌 Constantes do sistema
if (!defined('NOME_SISTEMA')) {
    define('NOME_SISTEMA', 'Sistema de Gestão Biblioteca CNI');
}
if (!defined('VERSAO_SISTEMA')) {
    define('VERSAO_SISTEMA', '1.0');
}
if (!defined('EMAIL_SUPORTE')) {
    define('EMAIL_SUPORTE', 'suporte@cni.com.br');
}
