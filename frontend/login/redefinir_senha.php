<?php
session_start();
require_once __DIR__ . '/../../backend/config/config.php';
require_once __DIR__ . '/../../backend/config/env.php';
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <title>Recuperar Senha - <?= NOME_SISTEMA ?></title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="<?= URL_BASE ?>frontend/assets/css/base.css">
  <link rel="stylesheet" href="<?= URL_BASE ?>frontend/assets/css/layout.css">
  <link rel="stylesheet" href="<?= URL_BASE ?>frontend/assets/css/components.css">
  <link rel="stylesheet" href="<?= URL_BASE ?>frontend/assets/css/utilities.css">
  <link rel="stylesheet" href="<?= URL_BASE ?>frontend/assets/css/pages/login.css">
</head>
<body>

  <div class="login-box">
    <h2>🔑 Recuperar Senha</h2>

    <?php if (isset($_SESSION['erro'])): ?>
      <div class="alerta-erro"><?= $_SESSION['erro']; unset($_SESSION['erro']); ?></div>
    <?php endif; ?>

    <?php if (isset($_SESSION['sucesso'])): ?>
      <div class="alerta-sucesso"><?= $_SESSION['sucesso']; unset($_SESSION['sucesso']); ?></div>
    <?php endif; ?>

    <form action="<?= URL_BASE ?>backend/controllers/auth/redefinir_senha_valida.php" method="POST">
      <div class="form-group">
        <input type="email" name="email" class="form-control" placeholder="Digite seu e-mail" required>
      </div>
      <button type="submit" class="btn">Enviar Link de Redefinição</button>
    </form>

    <div class="link-cadastro mt-3">
      <a href="login_user.php">← Voltar ao Login</a>
    </div>
  </div>

</body>
</html>
