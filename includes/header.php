<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/session.php';
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <title><?= htmlspecialchars(NOME_SISTEMA) ?></title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <!-- Temas customizáveis -->
  <link rel="stylesheet" href="<?= URL_BASE ?>assets/css/estilo-base.css" id="tema-base">
  <link rel="stylesheet" href="<?= URL_BASE ?>assets/css/estilo-medio.css" id="tema-medio" disabled>
  <link rel="stylesheet" href="<?= URL_BASE ?>assets/css/estilo-dark.css" id="tema-dark" disabled>

  <!-- Bootstrap e Ícones -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body>
  <!-- Menu principal -->
  <?php include_once __DIR__ . '/menu.php'; ?>

  <!-- Conteúdo da página -->
  <div class="container mt-4">
