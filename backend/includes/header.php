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
  <title><?= htmlspecialchars(NOME_SISTEMA) ?></title>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

  <link rel="stylesheet" href="<?= URL_BASE ?>frontend/assets/css/base.css">
  <link rel="stylesheet" href="<?= URL_BASE ?>frontend/assets/css/layout.css">
  <link rel="stylesheet" href="<?= URL_BASE ?>frontend/assets/css/componentes.css">
  <link rel="stylesheet" href="<?= URL_BASE ?>frontend/assets/css/estilo-claro.css">

  <?php if ($modo === 'medio'): ?>
    <link rel="stylesheet" href="<?= URL_BASE ?>frontend/assets/css/estilo-medio.css">
  <?php elseif ($modo === 'escuro' || $modo === 'dark'): ?>
    <link rel="stylesheet" href="<?= URL_BASE ?>frontend/assets/css/estilo-dark.css">
  <?php endif; ?>

  <script src="<?= URL_BASE ?>frontend/assets/js/tema.js" defer></script>

  <?php if (!empty($extraStyles)) echo $extraStyles; ?>
</head>
<body class="d-flex flex-column min-vh-100">
<div class="container py-4">
