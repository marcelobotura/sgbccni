<?php 
// ✅ Carrega variáveis de ambiente e sessão
require_once __DIR__ . '/env.php';
require_once __DIR__ . '/../includes/session.php';

$erros = [];
$avisos = [];
$sucessos = [];

// 🔌 Conecta ao banco de dados com PDO
try {
    $pdo = new PDO(
        "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4",
        DB_USER,
        DB_PASS,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ]
    );
    // Teste simples da conexão
    $pdo->query("SELECT 1");
    $sucessos[] = "✅ Conexão com o banco de dados funcionando.";
} catch (PDOException $e) {
    $erros[] = "❌ Falha na conexão com o banco: " . $e->getMessage();
}

// 🔐 Teste sessão
if (session_status() === PHP_SESSION_ACTIVE) {
    $sucessos[] = "✅ Sessão ativa.";
} else {
    $erros[] = "❌ Sessão não está ativa.";
}

// 🧾 Teste escrita em logs/
$logTest = dirname(__DIR__) . '/logs/teste_log.txt';
if (@file_put_contents($logTest, 'Teste de escrita em ' . date('Y-m-d H:i:s'))) {
    $sucessos[] = "✅ Permissão de escrita em /logs.";
    unlink($logTest);
} else {
    $erros[] = "❌ Sem permissão de escrita em /logs.";
}

// 🖼️ Teste permissão em uploads/
$uploadPath = dirname(__DIR__) . '/../uploads/teste.txt';
if (@file_put_contents($uploadPath, 'teste')) {
    $sucessos[] = "✅ Permissão de escrita em /uploads.";
    unlink($uploadPath);
} else {
    $erros[] = "❌ Sem permissão de escrita em /uploads.";
}

// 🌐 Teste constantes do sistema
if (defined('URL_BASE')) {
    $sucessos[] = "✅ URL_BASE definida como: " . URL_BASE;
} else {
    $erros[] = "❌ Constante URL_BASE não definida.";
}

if (defined('NOME_SISTEMA')) {
    $sucessos[] = "✅ NOME_SISTEMA: " . NOME_SISTEMA;
}

if (defined('VERSAO_SISTEMA')) {
    $sucessos[] = "✅ VERSAO_SISTEMA: " . VERSAO_SISTEMA;
}

// 🎨 Tema atual
$tema = $_COOKIE['modo_tema'] ?? 'claro';
$sucessos[] = "🎨 Tema atual (via cookie): $tema";

// 🌍 Ambiente
$sucessos[] = "🌎 Ambiente atual: " . (defined('ENV_DEV') && ENV_DEV ? "Desenvolvimento (DEV)" : "Produção");
?>
