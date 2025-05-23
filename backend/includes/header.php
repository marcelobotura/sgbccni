<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/session.php';

// Define o modo de tema via cookie ou padrão
$modo = $_COOKIE['modo_tema'] ?? 'claro';

// Define título se ainda não estiver definido
if (!defined('NOME_SISTEMA')) {
    define('NOME_SISTEMA', 'Biblioteca CNI');
}
?>

<!DOCTYPE html>
<html lang="pt-br" data-tema="<?= htmlspecialchars($modo) ?>">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">

  <title><?= htmlspecialchars(NOME_SISTEMA) ?></title>

  <!-- Ícones Bootstrap -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
<link rel="stylesheet" href="<?= URL_BASE ?>frontend/assets/css/admin.css">

<link rel="stylesheet" href="<?= URL_BASE ?>frontend/assets/css/estilo-base.css">
<link rel="stylesheet" href="<?= URL_BASE ?>frontend/assets/css/admin.css">
  <!-- Estilos principais -->
  <link href="<?= URL_BASE ?>assets/css/estilo-base.css" rel="stylesheet">

  <!-- Tema escuro ou médio -->
  <?php if ($modo === 'dark'): ?>
    <link href="<?= URL_BASE ?>assets/css/estilo-dark.css" rel="stylesheet">
  <?php elseif ($modo === 'medio'): ?>
    <link href="<?= URL_BASE ?>assets/css/estilo-medio.css" rel="stylesheet">
  <?php endif; ?>

  <!-- Bootstrap 5 CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

  <!-- Custom CSS por página (opcional) -->
  <?php if (!empty($extraStyles)) echo $extraStyles; ?>
</head>

<body class="d-flex flex-column min-vh-100">
<div class="container py-4">
