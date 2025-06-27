<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/env.php';

if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_tipo'] !== 'admin') {
    header('Location: ../../frontend/login/login_admin.php');
    exit;
}

// 🗓️ Data atual para nome do backup
$data = date('Y-m-d_H-i-s');
$arquivo_backup = "backup_banco_$data.sql";

// 🔧 Caminho do mysqldump (ajuste se necessário)
$mysqldump = "C:\\xampp\\mysql\\bin\\mysqldump.exe";

if (!file_exists($mysqldump)) {
    die("<div style='color:red;'>❌ Erro: mysqldump não encontrado no caminho <strong>$mysqldump</strong>.<br> Verifique se o caminho está correto.</div>");
}

// 🏗️ Monta o comando mysqldump
$comando = "\"$mysqldump\" --user=\"$user\" --password=\"$pass\" --host=\"$host\" \"$db\"";

// 🔥 Headers para download
header('Content-Type: application/octet-stream');
header("Content-Disposition: attachment; filename=\"$arquivo_backup\"");
header('Pragma: no-cache');
header('Expires: 0');

// 🚀 Executa o comando e envia o conteúdo como download
$process = popen($comando, 'r');
if ($process === false) {
    die("<div style='color:red;'>❌ Erro ao executar o comando de backup.</div>");
}
while (!feof($process)) {
    echo fread($process, 4096);
}
pclose($process);
exit;
?>
