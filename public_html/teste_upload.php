<?php
$arquivo = __DIR__ . '/../uploads/teste.txt';

if (file_put_contents($arquivo, 'teste')) {
    echo "✅ Sucesso: Conseguiu gravar na pasta uploads.";
    unlink($arquivo);
} else {
    echo "❌ Erro: Não conseguiu gravar na pasta uploads.";
}
?>
