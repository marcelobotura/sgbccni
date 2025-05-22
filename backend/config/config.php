<?php
// 🌐 URL base para links absolutos
if (!defined('URL_BASE')) {
    define('URL_BASE', 'http://localhost/sgbccni/public_html/'); // altere para domínio real
}

// ⚙️ Ambiente: true = desenvolvimento | false = produção
define('ENV_DEV', true);

// ⏰ Fuso horário
date_default_timezone_set('America/Sao_Paulo');

// 📁 Diretório de logs
$logDir = __DIR__ . '/../logs';
if (!file_exists($logDir)) {
    mkdir($logDir, 0777, true); // cria a pasta se não existir
}

// 🔧 Configuração de exibição e log de erros
ini_set('display_errors', ENV_DEV ? '1' : '0');
ini_set('log_errors', '1');
ini_set('error_log', $logDir . '/php-error.log');

// 🔐 Dados de conexão
$host    = 'localhost';
$usuario = 'root';
$senha   = '';  // coloque a senha do banco, se necessário
$banco   = 'sgbccni';

// 🔌 Conexão com o banco
$conn = new mysqli($host, $usuario, $senha, $banco);
$conn->set_charset("utf8");

if ($conn->connect_error) {
    error_log("Erro de conexão: " . $conn->connect_error);
    die("Erro ao conectar ao banco de dados.");
}

// 📌 Informações adicionais
define('NOME_SISTEMA', 'Sistema de Gestão Biblioteca CNI');
define('VERSAO_SISTEMA', '1.0');
define('EMAIL_SUPORTE', 'suporte@cni.com.br');
