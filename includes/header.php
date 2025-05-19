<?php
require_once __DIR__ . '/config.php';

if (!defined('NOME_SISTEMA')) {
  define('NOME_SISTEMA', 'Biblioteca CNI');
}

$modo = $_COOKIE['modo_tema'] ?? 'dark';
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <title><?= htmlspecialchars(NOME_SISTEMA) ?></title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <link href="<?= URL_BASE ?>assets/css/style.css" rel="stylesheet">
</head>
<body class="<?= $modo === 'light' ? 'light-mode' : '' ?>">

<!-- Botão fixo de tema -->
<button class="toggle-theme-btn" onclick="alternarTema()">🌓 Tema</button>

<!-- Menu Moderno -->
<header class="shadow-sm mb-4">
  <div class="container d-flex justify-content-between align-items-center py-3">
    <div class="logo-bibli">
      <i class="bi bi-book-half"></i> Biblioteca CNI
    </div>
    <nav>
  <ul class="nav">
    <li class="nav-item">
      <a class="nav-link px-3 text-white" href="<?= URL_BASE ?>public/index.php"><i class="bi bi-house-door"></i> Home</a>
    </li>
    <li class="nav-item">
      <a class="nav-link px-3 text-white" href="<?= URL_BASE ?>public/pesquisador.php"><i class="bi bi-search"></i> Buscar</a>
    </li>
    <li class="nav-item">
      <a class="nav-link px-3 text-white" href="<?= URL_BASE ?>admin/index.php"><i class="bi bi-speedometer2"></i> Painel</a>
    </li>
  </ul>
</nav>

  </div>
</header>
