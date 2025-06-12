<?php
// ✅ Inicia a sessão corretamente antes de qualquer verificação
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../backend/config/config.php';

$erros = [];
$avisos = [];
$sucessos = [];

// ✅ Banco de dados
try {
    $conn->query("SELECT 1");
    $sucessos[] = "✅ Conexão com o banco de dados funcionando.";
} catch (Exception $e) {
    $erros[] = "❌ Falha na conexão com o banco: " . $e->getMessage();
}

// ✅ Sessão
if (session_status() === PHP_SESSION_ACTIVE) {
    $sucessos[] = "✅ Sessão ativa.";
} else {
    $erros[] = "❌ Sessão não está ativa.";
}

// ✅ Escrita em /logs
$logTest = __DIR__ . '/../backend/logs/teste_log.txt';
if (@file_put_contents($logTest, 'Teste de escrita em ' . date('Y-m-d H:i:s'))) {
    $sucessos[] = "✅ Permissão de escrita em /logs.";
    unlink($logTest);
} else {
    $erros[] = "❌ Sem permissão de escrita em /logs.";
}

// ✅ Escrita em /uploads
$uploadTest = __DIR__ . '/../uploads/teste.txt';
if (@file_put_contents($uploadTest, 'teste')) {
    $sucessos[] = "✅ Permissão de escrita em /uploads.";
    unlink($uploadTest);
} else {
    $erros[] = "❌ Sem permissão de escrita em /uploads.";
}

// ✅ Constantes
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

// ✅ Tema
$tema = $_COOKIE['modo_tema'] ?? 'claro';
$sucessos[] = "🎨 Tema atual (via cookie): $tema";

// ✅ Ambiente
$sucessos[] = "🌎 Ambiente atual: " . (defined('ENV_DEV') && ENV_DEV ? "Desenvolvimento (DEV)" : "Produção");
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <title>Diagnóstico do Sistema</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      padding: 2rem;
    }
  </style>
</head>
<body>
  <h2 class="mb-4">🔍 Diagnóstico do Sistema - Biblioteca CNI</h2>

  <?php foreach ($sucessos as $msg): ?>
    <div class="alert alert-success"><?= $msg ?></div>
  <?php endforeach; ?>

  <?php foreach ($erros as $msg): ?>
    <div class="alert alert-danger"><?= $msg ?></div>
  <?php endforeach; ?>

  <?php foreach ($avisos as $msg): ?>
    <div class="alert alert-warning"><?= $msg ?></div>
  <?php endforeach; ?>

  <p class="text-muted mt-5">🛠️ Página de diagnóstico temporária. Remova em produção.</p>
</body>
</html>
