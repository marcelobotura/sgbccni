<?php
// 🌐 URL base para links absolutos
if (!defined('URL_BASE')) {
    define('URL_BASE', 'http://localhost/sgbccni/public_html/'); // 🛠️ Altere para o domínio real em produção
}

// ⚙️ Ambiente: true = desenvolvimento | false = produção
define('ENV_DEV', true);

// ⏰ Fuso horário padrão
date_default_timezone_set('America/Sao_Paulo');

// 📁 Diretório de logs (cria se não existir)
$logDir = __DIR__ . '/../logs';
if (!is_dir($logDir)) {
    mkdir($logDir, 0777, true);
}

// 🔧 Configuração de erros e log
ini_set('display_errors', ENV_DEV ? '1' : '0');
ini_set('log_errors', '1');
ini_set('error_log', $logDir . '/php-error.log');

// 🔐 Dados de conexão com o banco
$host    = 'localhost';
$usuario = 'root';
$senha   = ''; // 🛡️ Adicione senha real em produção
$banco   = 'sgbccni';

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
        die("Erro ao conectar ao banco de dados. Tente mais tarde.");
    }
}

// 📌 Informações do sistema
define('NOME_SISTEMA', 'Sistema de Gestão Biblioteca CNI');
define('VERSAO_SISTEMA', '1.0');
define('EMAIL_SUPORTE', 'suporte@cni.com.br');
