<?php
<<<<<<< Updated upstream
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/env.php';

if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_tipo'] !== 'admin') {
    header('Location: ../../frontend/login/login_admin.php');
    exit;
}
=======
session_start();

// ✅ Inclui as configurações do ambiente, agora com constantes
// CORREÇÃO: O caminho foi simplificado para './env.php' pois env.php está na mesma pasta.
require_once __DIR__ . '/env.php'; //

// Inclui o protect_admin para garantir que apenas admins acessem
require_once ROOT_PATH . 'backend/includes/protect_admin.php'; //
// protect_admin.php já deve lidar com o redirecionamento se não for admin.
// remove a verificação manual de !isset($_SESSION['usuario_id']) || $_SESSION['usuario_tipo'] !== 'admin'
// pois ela já estará contida no protect_admin.php
>>>>>>> Stashed changes

// 🗓️ Data atual para nome do backup
$data = date('Y-m-d_H-i-s');
$arquivo_backup = "backup_banco_$data.sql";

// 🔧 Caminho do mysqldump (obtido de env.php)
$mysqldump = MYSQLDUMP_PATH; //

// Verifica se o mysqldump existe no caminho especificado
if (!file_exists($mysqldump)) {
    // Registra o erro em vez de dar die() diretamente para o usuário
    error_log("❌ Erro mysqldump: mysqldump não encontrado em $mysqldump. Verifique o caminho em env.php.");
    // Redireciona o administrador para uma página de erro ou de volta para o dashboard
    $_SESSION['erro'] = "Erro interno: Ferramenta de backup não encontrada. Contate o suporte.";
    header('Location: ' . URL_BASE . 'frontend/admin/pages/configuracoes.php'); // Ou para o dashboard
    exit;
}

// 🏗️ Monta o comando mysqldump usando as constantes do env.php
$comando = "\"$mysqldump\" --user=" . DB_USER . " --password=" . DB_PASS . " --host=" . DB_HOST . " " . DB_NAME; //

// **IMPORTANTE:** Se DB_PASS estiver vazio, o argumento --password deve ser omitido
// ou usado com --password= sem valor para evitar problemas em alguns sistemas.
// No entanto, para segurança, é altamente recomendado que DB_PASS nunca esteja vazio.
if (DB_PASS === '') {
    $comando = "\"$mysqldump\" --user=" . DB_USER . " --host=" . DB_HOST . " " . DB_NAME;
}

<<<<<<< Updated upstream
// 🏗️ Monta o comando mysqldump
$comando = "\"$mysqldump\" --user=\"$user\" --password=\"$pass\" --host=\"$host\" \"$db\"";
=======
>>>>>>> Stashed changes

// 🔥 Headers para download
header('Content-Type: application/octet-stream');
header("Content-Disposition: attachment; filename=\"$arquivo_backup\"");
header('Pragma: no-cache');
header('Expires: 0');
// Previne que o PHP-FPM/Nginx bufferize a saída, garantindo que o download comece imediatamente
// Isso pode ser necessário dependendo da configuração do servidor.
if (function_exists('apache_setenv')) {
    apache_setenv('no-gzip', '1');
}
ini_set('zlib.output_compression', 'Off');
ini_set('output_buffering', 'Off');
ini_set('implicit_flush', 'On');
ob_implicit_flush(1);
for ($i = 0; $i < 4096; $i++) { // Preencher buffer para alguns servidores
    echo ' ';
}
flush();

// 🚀 Executa o comando e envia o conteúdo como download
$process = popen($comando, 'r');
<<<<<<< Updated upstream
if ($process === false) {
    die("<div style='color:red;'>❌ Erro ao executar o comando de backup.</div>");
}
while (!feof($process)) {
    echo fread($process, 4096);
=======
if (!$process) {
    error_log("❌ Erro popen: Falha ao executar o comando mysqldump.");
    // Dependendo do ambiente, pode ser tarde para redirecionar ou definir sessão aqui
    // já que os headers de download já foram enviados.
    // Uma mensagem de erro simples no final do arquivo pode ser uma opção.
    echo "Erro ao gerar o backup. Verifique os logs do servidor.";
    exit;
>>>>>>> Stashed changes
}

while (!feof($process)) {
    // Lê em blocos maiores para melhor performance
    echo fread($process, 8192); // Aumentei o tamanho do buffer de leitura
    flush(); // Envia o buffer para o navegador imediatamente
}
$exitCode = pclose($process);

if ($exitCode !== 0) {
    error_log("❌ Erro mysqldump: mysqldump terminou com código de erro $exitCode.");
    // Pode tentar exibir uma mensagem para o usuário, mas como os headers já foram enviados,
    // será adicionado ao final do arquivo SQL baixado ou no console do navegador.
    echo "\n-- ATENÇÃO: O backup pode estar incompleto ou conter erros. Código de saída: $exitCode --\n";
}

exit;
?>