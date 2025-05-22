<?php
/**
 * 🌱 Carrega variáveis do arquivo .env
 * Suporta comentários, espaços, valores com aspas e segurança contra erros
 */
function carregarEnv($caminho) {
    if (!file_exists($caminho)) return;

    $linhas = file($caminho, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

    foreach ($linhas as $linha) {
        $linha = trim($linha);

        // Ignora comentários e linhas malformadas
        if ($linha === '' || str_starts_with($linha, '#') || !str_contains($linha, '=')) {
            continue;
        }

        list($chave, $valor) = explode('=', $linha, 2);

        // Remove aspas se houver
        $chave = trim($chave);
        $valor = trim($valor, " \t\n\r\0\x0B\"'");

        // Define como variável de ambiente
        putenv("$chave=$valor");

        // Também pode ser usado com $_ENV e $_SERVER se necessário
        $_ENV[$chave] = $valor;
        $_SERVER[$chave] = $valor;
    }
}

// ✅ Executa o carregamento
carregarEnv(__DIR__ . '/../.env');
