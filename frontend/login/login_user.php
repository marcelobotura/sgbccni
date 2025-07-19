<?php 
// Define URL_BASE se ainda não estiver definida
if (!defined('URL_BASE')) {
  define('URL_BASE', '/sgbccni/'); // ajuste se necessário
}

// Inicia sessão apenas se ainda não estiver ativa
if (session_status() !== PHP_SESSION_ACTIVE) {
  session_start();
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <title>Login do Usuário</title>

  <!-- Estilos profissionais -->
  <link rel="stylesheet" href="<?= URL_BASE ?>frontend/assets/css/base.css">
  <link rel="stylesheet" href="<?= URL_BASE ?>frontend/assets/css/layout.css">
  <link rel="stylesheet" href="<?= URL_BASE ?>frontend/assets/css/components.css">
  <link rel="stylesheet" href="<?= URL_BASE ?>frontend/assets/css/utilities.css">
  <link rel="stylesheet" href="<?= URL_BASE ?>frontend/assets/css/pages/login.css">
  <link rel="stylesheet" href="<?= URL_BASE ?>frontend/assets/css/themes/light.css" id="theme-style">
</head>
<body>

  <div class="login-box usuario">
    <h2>Login Usuário</h2>

    <?php if (isset($_GET['erro'])): ?>
      <div class="alerta-erro">❌ Usuário ou senha inválidos.</div>
    <?php endif; ?>

    <?php if (isset($_GET['logout'])): ?>
      <div class="alerta-sucesso">✅ Logout realizado com sucesso.</div>
    <?php endif; ?>
    <form action="<?= URL_BASE ?>backend/controllers/auth/login_valida.php" method="POST">
      <input type="hidden" name="acao" value="login">
      <input type="hidden" name="origem" value="usuario">

      <div class="form-group">
        <input type="email" name="email" class="form-control" placeholder="Email" required>
      </div>

      <div class="form-group">
        <input type="password" name="senha" class="form-control" id="senha" placeholder="Senha" required>
        <button type="button" class="toggle-password" onclick="toggleSenha(this)">👁️</button>
      </div>

      <button type="submit" class="btn">Entrar</button>
    </form>

    <div class="link-cadastro">
      <p>Não tem uma conta?</p>
      <a href="<?= URL_BASE ?>frontend/login/register_user.php">Cadastre-se</a>
    </div>
  </div>

  <script>
    function toggleSenha(el) {
      const senhaInput = document.getElementById('senha');
      if (senhaInput.type === 'password') {
        senhaInput.type = 'text';
        el.textContent = '🙈';
      } else {
        senhaInput.type = 'password';
        el.textContent = '👁️';
      }
    }
  </script>
</body>
</html>
