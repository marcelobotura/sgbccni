<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/session.php';

$modo = $_COOKIE['modo_tema'] ?? 'claro';
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <title><?= htmlspecialchars(NOME_SISTEMA) ?></title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <!-- Temas customizáveis -->
  <link rel="stylesheet" href="<?= URL_BASE ?>assets/css/estilo-base.css">
  <link rel="stylesheet" href="<?= URL_BASE ?>assets/css/estilo-medio.css" <?= $modo === 'medio' ? '' : 'disabled' ?>>
  <link rel="stylesheet" href="<?= URL_BASE ?>assets/css/estilo-dark.css" <?= $modo === 'dark' ? '' : 'disabled' ?>>

  <!-- Bootstrap e Ícones -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body>
  <!-- Menu principal -->
  <?php include_once __DIR__ . '/menu.php'; ?>

  
  <script>
    function alternarTema() {
      let temaAtual = getCookie('modo_tema') || 'claro';
      let proximoTema = temaAtual === 'claro' ? 'medio' : (temaAtual === 'medio' ? 'dark' : 'claro');
      document.cookie = 'modo_tema=' + proximoTema + '; path=/';
      location.reload();
    }
    function getCookie(nome) {
      let v = document.cookie.match('(^|;)\\s*' + nome + '\\s*=\\s*([^;]+)');
      return v ? v.pop() : '';
    }
  </script>
  <div class="container mt-4">
