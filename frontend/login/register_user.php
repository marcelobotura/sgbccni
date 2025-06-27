<?php
session_start();
define('BASE_PATH', dirname(__DIR__, 2) . '/backend');
require_once BASE_PATH . '/config/config.php';
require_once BASE_PATH . '/config/env.php';
require_once BASE_PATH . '/includes/session.php';

if (!defined('URL_BASE')) {
  define('URL_BASE', '/sgbccni/'); // ajuste conforme sua pasta
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <title>Cadastro de Usuário</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- CSS Padronizado -->
  <link rel="stylesheet" href="../assets/css/base.css">
  <link rel="stylesheet" href="../assets/css/layout.css">
  <link rel="stylesheet" href="../assets/css/components.css">
  <link rel="stylesheet" href="../assets/css/utilities.css">
  <link rel="stylesheet" href="../assets/css/pages/login.css">
  <link rel="stylesheet" href="../assets/css/themes/light.css" id="theme-style">
</head>
<body>

  <div class="login-box">
    <h2>Cadastro de Usuário</h2>

    <?php if (isset($_SESSION['erro'])): ?>
      <div class="alerta-erro"><?= $_SESSION['erro']; unset($_SESSION['erro']); ?></div>
    <?php endif; ?>

    <?php if (isset($_SESSION['sucesso'])): ?>
      <div class="alerta-sucesso"><?= $_SESSION['sucesso']; unset($_SESSION['sucesso']); ?></div>
    <?php endif; ?>

    <form action="<?= URL_BASE ?>backend/controllers/auth/register_valida.php" method="POST">
      <!-- ✅ Campo obrigatório para redirecionamento correto -->
      <input type="hidden" name="tipo" value="usuario">

      <div class="form-group">
        <input type="text" name="nome" class="form-control" placeholder="Nome completo" required value="<?= htmlspecialchars($_SESSION['form_data']['nome'] ?? '') ?>">
      </div>

      <div class="form-group">
        <input type="email" name="email" class="form-control" placeholder="Email" required value="<?= htmlspecialchars($_SESSION['form_data']['email'] ?? '') ?>">
      </div>

      <div class="form-group">
        <input type="password" name="senha" id="senha" class="form-control" placeholder="Senha" required>
        <button type="button" class="toggle-password" onclick="toggleSenha(this, 'senha')">👁️</button>
      </div>

      <div class="form-group">
        <input type="password" name="senha2" id="senha2" class="form-control" placeholder="Confirmar Senha" required>
        <button type="button" class="toggle-password" onclick="toggleSenha(this, 'senha2')">👁️</button>
      </div>

      <button type="submit" class="btn">Cadastrar</button>
    </form>

    <div class="link-cadastro">
      <p>Já tem uma conta? <a href="login_user.php">Entrar</a></p>
    </div>
  </div>

  <script>
    function toggleSenha(el, id) {
      const input = document.getElementById(id);
      if (input.type === 'password') {
        input.type = 'text';
        el.textContent = '🙈';
      } else {
        input.type = 'password';
        el.textContent = '👁️';
      }
    }
  </script>

  <?php unset($_SESSION['form_data']); ?>
</body>
</html>
