<?php
// Caminho: frontend/login/register.php
session_start();
require_once __DIR__ . '/../../backend/config/env.php';
require_once __DIR__ . '/../../backend/includes/session.php';
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <title>Cadastro - <?= NOME_SISTEMA ?></title>
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- Bootstrap + CSS Base -->
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
    <div class="card-header login-header bg-success text-white text-center">
      <h4 class="mb-0">Criar Conta</h4>
    </div>

    <!-- Corpo -->
    <div class="card-body">
      <?php if (!empty($_SESSION['erro'])): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($_SESSION['erro']); unset($_SESSION['erro']); ?></div>
      <?php endif; ?>
      <?php if (!empty($_SESSION['sucesso'])): ?>
        <div class="alert alert-success"><?= htmlspecialchars($_SESSION['sucesso']); unset($_SESSION['sucesso']); ?></div>
      <?php endif; ?>

      <!-- Formulário -->
      <form method="POST" action="<?= URL_BASE ?>backend/controllers/autenticacao/registro.php" novalidate>
        <div class="mb-3">
          <label for="nome" class="form-label">Nome</label>
          <input type="text" name="nome" id="nome" class="form-control" required placeholder="Seu nome completo">
        </div>

        <div class="mb-3">
          <label for="email" class="form-label">E-mail</label>
          <input type="email" name="email" id="email" class="form-control" required placeholder="usuario@exemplo.com">
        </div>

        <div class="mb-3 position-relative">
          <label for="senhaRegistro" class="form-label">Senha</label>
          <input type="password" name="senha" id="senhaRegistro" class="form-control" required placeholder="********">
          <span class="toggle-password" onclick="toggleSenha('senhaRegistro')" title="Mostrar/ocultar senha">👁️</span>
        </div>

        <div class="d-grid">
          <button type="submit" class="btn btn-login">Cadastrar</button>
        </div>
      </form>

      <div class="mt-3 text-center">
        <small>Já tem uma conta? <a href="login.php">Entrar</a></small>
      </div>
    </div>
  </div>
</div>

<script>
  function toggleSenha(id) {
    const campo = document.getElementById(id);
    const icone = event.target;

    if (campo.type === 'password') {
      campo.type = 'text';
      icone.textContent = '🙈'; // Fecha o olho
    } else {
      campo.type = 'password';
      icone.textContent = '👁️'; // Abre o olho
    }
  }
</script>

</body>
</html>
