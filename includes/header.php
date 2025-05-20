<?php
include_once(__DIR__ . '/../config/config.php');

if (!defined('NOME_SISTEMA')) {
  define('NOME_SISTEMA', 'Biblioteca CNI');
}

$modo = $_COOKIE['modo_tema'] ?? 'dark';
$logado = isset($_SESSION['admin_logado']) && $_SESSION['admin_logado'] === true;
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
  <link rel="icon" href="<?= URL_BASE ?>assets/img/favicon.ico" type="image/x-icon">
</head>
<body class="<?= $modo === 'light' ? 'light-mode' : '' ?>">

<!-- Botão de alternância de tema -->
<button class="toggle-theme-btn btn btn-sm position-fixed top-0 end-0 m-3 z-3" onclick="alternarTema()" title="Alternar Tema">
  🌓
</button>

<!-- Cabeçalho -->
<header class="shadow-sm mb-4" style="background-color: var(--card-color); border-bottom: 1px solid var(--border-color);">
  <div class="container d-flex justify-content-between align-items-center py-3">
    <div class="logo-bibli h5 mb-0">
      <i class="bi bi-book-half"></i> <?= NOME_SISTEMA ?>
    </div>
    <nav>
      <ul class="nav">
        <li class="nav-item">
          <a class="nav-link px-3" style="color: var(--text-color);" href="<?= URL_BASE ?>public_html/index.php">
            <i class="bi bi-house-door"></i> Home
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link px-3" style="color: var(--text-color);" href="<?= URL_BASE ?>public_html/pesquisador.php">
            <i class="bi bi-search"></i> Buscar
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link px-3" style="color: var(--text-color);" href="<?= URL_BASE ?>admin/index.php">
            <i class="bi bi-speedometer2"></i> Painel
          </a>
        </li>
        <?php if ($logado): ?>
        <li class="nav-item">
          <a class="nav-link px-3" style="color: var(--text-color);" href="<?= URL_BASE ?>admin/logout.php">
            <i class="bi bi-box-arrow-right"></i> Sair
          </a>
        </li>
        <?php endif; ?>
      </ul>
    </nav>
  </div>
</header>
