<?php
// Caminho: frontend/login/login.php
session_start();
require_once __DIR__ . '/../../backend/config/env.php';
require_once __DIR__ . '/../../backend/includes/session.php';
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <title>Login - <?= NOME_SISTEMA ?></title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  
  <!-- Estilos principais -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="<?= URL_BASE ?>frontend/assets/css/base/base.css">
  <link rel="stylesheet" href="<?= URL_BASE ?>frontend/assets/css/base/colors.css">
  <link rel="stylesheet" href="<?= URL_BASE ?>frontend/assets/css/base/typography.css">
  <link rel="stylesheet" href="<?= URL_BASE ?>frontend/assets/css/base/reset.css">
  <link rel="stylesheet" href="<?= URL_BASE ?>frontend/assets/css/pages/login.css">
</head>
<body class="login-body">

<div class="container d-flex justify-content-center align-items-center min-vh-100">
  <div class="card login-card shadow">
    
    <!-- Cabeçalho -->
    <div class="card-header login-header text-white text-center">
      <h4 class="mb-0">Entrar no <?= NOME_SISTEMA ?></h4>
    </div>

    <!-- Corpo do card -->
    <div class="card-body">
      <?php if (!empty($_SESSION['erro'])): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($_SESSION['erro']); unset($_SESSION['erro']); ?></div>
      <?php endif; ?>
      <?php if (!empty($_SESSION['sucesso'])): ?>
        <div class="alert alert-success"><?= htmlspecialchars($_SESSION['sucesso']); unset($_SESSION['sucesso']); ?></div>
      <?php endif; ?>

      <!-- Formulário -->
      <form method="POST" action="<?= URL_BASE ?>backend/controllers/autenticacao/login.php" novalidate>
        <div class="mb-3">
          <label for="email" class="form-label">E-mail</label>
          <input type="email" name="email" id="email" class="form-control" required placeholder="usuario@exemplo.com">
        </div>

        <div class="mb-3 position-relative">
          <label for="senhaLogin" class="form-label">Senha</label>
          <input type="password" name="senha" id="senhaLogin" class="form-control" required placeholder="********">
          <span class="toggle-password" onclick="toggleSenha('senhaLogin')" title="Mostrar/ocultar senha">👁️</span>
        </div>

        <div class="d-grid">
          <button type="submit" class="btn btn-login">Entrar</button>
        </div>
      </form>

      <div class="mt-3 text-center">
        <small>Não tem uma conta? <a href="register.php">Cadastre-se</a></small>
      </div>
    </div>
  </div>
</div>

<!-- Script para alternar visualização da senha -->
<script>
  function toggleSenha(id) {
    const campo = document.getElementById(id);
    const icone = event.target;

    if (campo.type === 'password') {
      campo.type = 'text';
      icone.textContent = '🙈';
    } else {
      campo.type = 'password';
      icone.textContent = '👁️';
    }
  }
</script>


</body>
</html>
