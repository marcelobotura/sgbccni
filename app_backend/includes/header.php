<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/session.php';

// Define o modo de tema via cookie
$modo = $_COOKIE['modo_tema'] ?? 'claro';
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <title><?= defined('NOME_SISTEMA') ? htmlspecialchars(NOME_SISTEMA) : 'Biblioteca CNI' ?></title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <!-- Temas customizáveis -->
  <link rel="stylesheet" href="<?= URL_BASE ?>assets/css/estilo-base.css">
  <link rel="stylesheet" href="<?= URL_BASE ?>assets/css/estilo-medio.css" <?= $modo === 'medio' ? '' : 'disabled' ?>>
  <link rel="stylesheet" href="<?= URL_BASE ?>assets/css/estilo-dark.css" <?= $modo === 'dark' ? '' : 'disabled' ?>>

  <!-- Bootstrap e ícones -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

  <!-- Tema alternador -->
  <script>
    function alternarTema() {
      const atual = getCookie('modo_tema') || 'claro';
      const proximo = atual === 'claro' ? 'medio' : (atual === 'medio' ? 'dark' : 'claro');
      document.cookie = 'modo_tema=' + proximo + '; path=/';
      location.reload();
    }

    function getCookie(nome) {
      const match = document.cookie.match('(^|;)\\s*' + nome + '\\s*=\\s*([^;]+)');
      return match ? match.pop() : '';
    }
  </script>
</head>
<body class="bg-body">
  <!-- Menu principal -->
  <?php include_once __DIR__ . '/menu.php'; ?>

  <!-- Botão flutuante de troca de tema -->
  <button onclick="alternarTema()" class="btn btn-outline-secondary position-fixed bottom-0 end-0 m-3 shadow rounded-circle" title="Alternar tema">
    <i class="bi bi-circle-half"></i>
  </button>

  <!-- Início do conteúdo -->
  <div class="container mt-4">
