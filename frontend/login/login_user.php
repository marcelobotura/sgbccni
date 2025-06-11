<?php session_start(); ?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <title>Login do Usuário</title>

  <!-- Estilos profissionais -->
  <link rel="stylesheet" href="../assets/css/base.css">
  <link rel="stylesheet" href="../assets/css/layout.css">
  <link rel="stylesheet" href="../assets/css/components.css">
  <link rel="stylesheet" href="../assets/css/utilities.css">
  <link rel="stylesheet" href="../assets/css/pages/login.css">
  <link rel="stylesheet" href="../assets/css/themes/light.css" id="theme-style">
</head>
<body>

  <div class="login-box usuario">
    <h2>Login Usuário</h2>

    <?php if (isset($_GET['erro'])): ?>
      <div class="alerta-erro">Usuário ou senha inválidos.</div>
    <?php endif; ?>

    <form action="../../backend/controllers/auth/login_valida.php" method="POST">
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
      <a href="register_user.php">Cadastre-se</a>
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
