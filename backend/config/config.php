<?php
session_start();

// Inclui as configurações do ambiente
require_once __DIR__ . '/env.php';

// Inclui o protect_admin para garantir que apenas admins acessem
require_once ROOT_PATH . 'backend/includes/protect_admin.php';

// 🗓️ Data atual para nome do backup
$data = date('Y-m-d_H-i-s');
$arquivo_backup = "backup_banco_$data.sql";

// 🔧 Caminho do mysqldump (obtido de env.php)
$mysqldump = MYSQLDUMP_PATH;

// Verifica se o mysqldump existe no caminho especificado
if (!file_exists($mysqldump)) {
    error_log("❌ Erro mysqldump: mysqldump não encontrado em $mysqldump. Verifique o caminho em env.php.");
    $_SESSION['erro'] = "Erro interno: Ferramenta de backup não encontrada. Contate o suporte.";
    header('Location: ' . URL_BASE . 'frontend/admin/pages/configuracoes.php');
    exit;
}

// 🏗️ Monta o comando mysqldump usando as constantes do env.php
if (DB_PASS === '') {
    $comando = "\"$mysqldump\" --user=" . DB_USER . " --host=" . DB_HOST . " " . DB_NAME;
} else {
    $comando = "\"$mysqldump\" --user=" . DB_USER . " --password=" . DB_PASS . " --host=" . DB_HOST . " " . DB_NAME;
}

// 🔥 Headers para download
header('Content-Type: application/octet-stream');
header("Content-Disposition: attachment; filename=\"$arquivo_backup\"");
header('Pragma: no-cache');
header('Expires: 0');
if (function_exists('apache_setenv')) {
    apache_setenv('no-gzip', '1');
}
ini_set('zlib.output_compression', 'Off');
ini_set('output_buffering', 'Off');
ini_set('implicit_flush', 'On');
ob_implicit_flush(1);
for ($i = 0; $i < 4096; $i++) {
    echo ' ';
}
flush();

// 🚀 Executa o comando e envia o conteúdo como download
$process = popen($comando, 'r');
if (!$process) {
    error_log("❌ Erro popen: Falha ao executar o comando mysqldump.");
    echo "Erro ao gerar o backup. Verifique os logs do servidor.";
    exit;
}

while (!feof($process)) {
    echo fread($process, 8192);
    flush();
}
$exitCode = pclose($process);

if ($exitCode !== 0) {
    error_log("❌ Erro mysqldump: mysqldump terminou com código de erro $exitCode.");
    echo "\n-- ATENÇÃO: O backup pode estar incompleto ou conter erros. Código de saída: $exitCode --\n";
}

exit;
?>