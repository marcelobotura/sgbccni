<?php
// Carrega variáveis do arquivo .env
function carregarEnv($path) {
    if (!file_exists($path)) return;
    $linhas = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($linhas as $linha) {
        if (str_starts_with(trim($linha), '#')) continue;
        list($chave, $valor) = explode('=', $linha, 2);
        putenv(trim($chave) . '=' . trim($valor));
    }
}
carregarEnv(__DIR__ . '/../.env');
