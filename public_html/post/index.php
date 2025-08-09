<?php
// Caminho: public_html/post/index.php

require_once __DIR__ . '/../../backend/config/config.php';
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Postagens - <?= NOME_SISTEMA ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .message-container {
            min-height: 100vh;
        }
    </style>
</head>
<body>
    <div class="container d-flex flex-column justify-content-center align-items-center message-container">
        <h1 class="text-primary mb-3">📢 Informações em breve</h1>
        <p class="text-muted text-center">
            Esta página está em construção. Em breve, você encontrará aqui as últimas postagens e novidades.
        </p>
        <a href="<?= URL_BASE ?>public_html/" class="btn btn-outline-primary mt-3">⬅ Voltar para a página inicial</a>
    </div>
</body>
</html>
