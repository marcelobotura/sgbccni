<?php
// Caminho: public_html/erro_permissao_master.php
session_start();
require_once __DIR__ . '/../backend/config/env.php';
require_once __DIR__ . '/../backend/includes/session.php';
?>
<!DOCTYPE html>
<html lang="pt-br" data-theme="<?= htmlspecialchars($_COOKIE['tema'] ?? 'light') ?>">
<head>
  <meta charset="UTF-8">
  <title>Acesso Exclusivo - <?= NOME_SISTEMA ?></title>
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- Estilos -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="<?= URL_BASE ?>frontend/assets/css/base/base.css">
  <link rel="stylesheet" href="<?= URL_BASE ?>frontend/assets/css/themes/light.css">
  <link rel="stylesheet" href="<?= URL_BASE ?>frontend/assets/css/themes/dark.css">
</head>
<body class="d-flex justify-content-center align-items-center vh-100">

<div class="text-center p-4 border shadow rounded bg-white" style="max-width: 500px;">
  <h1 class="display-5 text-warning">🔐 Acesso Restrito</h1>
  <p class="lead mt-3">
    Esta área é <strong>exclusiva para usuários MASTER</strong>.
  </p>
  <p>
    Seu tipo de conta não possui permissão para visualizar esta página.
  </p>

  <?php if (isset($_SESSION['usuario_tipo'])): ?>
    <p class="text-muted small">
      Tipo atual da sua conta: <strong><?= htmlspecialchars($_SESSION['usuario_tipo']) ?></strong>
    </p>
  <?php endif; ?>

  <div class="d-flex justify-content-center gap-2 mt-4">
    <a href="<?= URL_BASE ?>public_html/index.php" class="btn btn-outline-primary">🏠 Página Inicial</a>
    <a href="<?= URL_BASE ?>frontend/login/login.php" class="btn btn-secondary">🔁 Trocar Conta</a>
  </div>
</div>

</body>
</html>
