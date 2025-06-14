<?php
session_start();

if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_tipo'] !== 'admin') {
    header('Location: ../login/login_admin.php');
    exit;
}

// 🔗 Conexão e variáveis do banco
require_once __DIR__ . '/../../../backend/config/env.php';

// 🗓️ Nome do arquivo de backup
$data = date('Y-m-d_H-i-s');
$arquivo_backup = "backup_banco_$data.sql";

// 🔥 Caminho para o mysqldump (ajuste se necessário)
$mysqldump = "C:\\xampp\\mysql\\bin\\mysqldump.exe";

if (!file_exists($mysqldump)) {
    die("<div style='color:red;'>❌ Erro: mysqldump não encontrado no caminho <strong>$mysqldump</strong>. Verifique o caminho correto.</div>");
}

// 🚀 Monta o comando mysqldump
$comando = "\"$mysqldump\" --user=$user --password=$pass --host=$host $db";

// 📦 Headers para download
header('Content-Type: application/octet-stream');
header("Content-Disposition: attachment; filename=\"$arquivo_backup\"");
header('Pragma: no-cache');
header('Expires: 0');

// 🔄 Executa o comando e envia o arquivo para download
$process = popen($comando, 'r');
while (!feof($process)) {
    echo fread($process, 4096);
}
pclose($process);
exit;
?>
