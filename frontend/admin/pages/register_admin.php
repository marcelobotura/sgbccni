<?php session_start(); ?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <title>Cadastrar Novo Admin</title>

  <!-- Estilo padronizado -->
  <link rel="stylesheet" href="../../assets/css/base.css">
  <link rel="stylesheet" href="../../assets/css/layout.css">
  <link rel="stylesheet" href="../../assets/css/components.css">
  <link rel="stylesheet" href="../../assets/css/utilities.css">
  <link rel="stylesheet" href="../../assets/css/pages/login.css">
  <link rel="stylesheet" href="../../assets/css/themes/light.css" id="theme-style">
</head>
<body>

  <div class="login-box">
    <h2>Criar Novo Administrador</h2>

    <?php if (isset($_SESSION['erro'])): ?>
      <div class="alerta-erro"><?= $_SESSION['erro']; unset($_SESSION['erro']); ?></div>
    <?php endif; ?>

    <?php if (isset($_SESSION['sucesso'])): ?>
      <div class="alerta-sucesso"><?= $_SESSION['sucesso']; unset($_SESSION['sucesso']); ?></div>
    <?php endif; ?>

    <form action="/sgbccni/frontend/admin/ajax/salvar_admin.php" method="POST">
      <div class="form-group">
        <input type="text" name="nome" class="form-control" placeholder="Nome completo" required>
      </div>

      <div class="form-group">
        <input type="email" name="email" class="form-control" placeholder="Email" required>
      </div>

      <div class="form-group">
        <input type="password" name="senha" class="form-control" id="senha" placeholder="Senha" required>
        <button type="button" class="toggle-password" onclick="toggleSenha(this)">👁️</button>
      </div>

      <button type="submit" class="btn">Cadastrar</button>
    </form>

    <div class="link-cadastro">
      <p><a href="../../login/login_admin.php">← Voltar para o login</a></p>
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
