<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/../backend/config/config.php';
require_once __DIR__ . '/../backend/includes/db.php';
require_once __DIR__ . '/../backend/includes/session.php';
?>

<!DOCTYPE html>
<html lang="pt-br" data-theme="<?= htmlspecialchars($_COOKIE['tema'] ?? 'light') ?>">
<head>
  <meta charset="UTF-8">
  <title>Login - <?= NOME_SISTEMA ?></title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="<?= URL_BASE ?>frontend/assets/css/base/base.css">
  <link rel="stylesheet" href="<?= URL_BASE ?>frontend/assets/css/pages/login.css">
  <link rel="stylesheet" href="<?= URL_BASE ?>frontend/assets/css/themes/light.css">
  <link rel="stylesheet" href="<?= URL_BASE ?>frontend/assets/css/themes/dark.css">
  <link rel="stylesheet" href="<?= URL_BASE ?>frontend/assets/css/themes/high-contrast.css">
</head>
<body class="login-body">

<div class="container d-flex justify-content-center align-items-center min-vh-100">
  <div class="card login-card shadow p-4" style="max-width: 400px; width: 100%">
    <div class="card-header login-header text-center border-0 bg-transparent">
      <h4 class="mb-0" style="color: var(--card-title)">Entrar</h4>
    </div>

    <div class="card-body">
      <?php if (!empty($_SESSION['erro'])): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($_SESSION['erro']); unset($_SESSION['erro']); ?></div>
      <?php endif; ?>
      <?php if (!empty($_SESSION['sucesso'])): ?>
        <div class="alert alert-success"><?= htmlspecialchars($_SESSION['sucesso']); unset($_SESSION['sucesso']); ?></div>
      <?php endif; ?>

      <form method="POST" action="autenticacao.php" novalidate>
        <div class="mb-3">
          <label for="email" class="form-label">E-mail</label>
          <input type="email" name="email" id="email" class="form-control" required placeholder="usuario@exemplo.com">
        </div>
        <div class="mb-3 position-relative">
          <label for="senhaLogin" class="form-label">Senha</label>
          <input type="password" name="senha" id="senhaLogin" class="form-control" required placeholder="********">
          <span class="toggle-password" onclick="toggleSenha('senhaLogin')">👁️</span>
        </div>
        <div class="d-grid">
          <button type="submit" class="btn btn-login" style="color: var(--btn-text)">Entrar</button>
        </div>
      </form>

      <div class="mt-3 text-center">
        <small>Não tem uma conta?
          <a href="<?= URL_BASE ?>frontend/login/register.php">Cadastre-se</a>
        </small>
      </div>
    </div>
  </div>
</div>

<script>
  function toggleSenha(id) {
    const campo = document.getElementById(id);
    const icone = event.target;
    campo.type = campo.type === 'password' ? 'text' : 'password';
    icone.textContent = campo.type === 'text' ? '🙈' : '👁️';
  }
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
