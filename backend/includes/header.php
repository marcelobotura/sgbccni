<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/session.php';

$modo = $_COOKIE['modo_tema'] ?? 'claro';

if (!defined('NOME_SISTEMA')) {
    define('NOME_SISTEMA', 'Biblioteca CNI');
}
?>

<!DOCTYPE html>
<html lang="pt-br" data-bs-theme="<?= htmlspecialchars($modo) ?>">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="theme-color" content="#007bff">

  <title><?= htmlspecialchars(NOME_SISTEMA) ?></title>

  <!-- Ícones Bootstrap -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

  <!-- 🎨 Estilos principais -->
  <link rel="stylesheet" href="<?= URL_BASE ?>assets/css/base.css">
  <link rel="stylesheet" href="<?= URL_BASE ?>assets/css/layout.css">
  <link rel="stylesheet" href="<?= URL_BASE ?>assets/css/componentes.css">
  <link rel="stylesheet" href="<?= URL_BASE ?>assets/css/estilo-claro.css"> <!-- sempre carrega claro -->

  <!-- 🎛️ Tema médio ou escuro (condicional) -->
  <?php if ($modo === 'medio'): ?>
    <link rel="stylesheet" href="<?= URL_BASE ?>assets/css/estilo-medio.css">
  <?php elseif ($modo === 'escuro' || $modo === 'dark'): ?>
    <link rel="stylesheet" href="<?= URL_BASE ?>assets/css/estilo-dark.css">
  <?php endif; ?>

  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

  <!-- JS de alternância de tema -->
  <script src="<?= URL_BASE ?>assets/js/tema.js" defer></script>

  <!-- Estilos extras (por página) -->
  <?php if (isset($extraStyles) && !empty($extraStyles)) echo $extraStyles; ?>
</head>

<body class="d-flex flex-column min-vh-100">
  <div class="container py-4">
