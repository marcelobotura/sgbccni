<!-- Caminho: frontend/login/register.php -->
<?php
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
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    .toggle-password {
      cursor: pointer;
      position: absolute;
      right: 1rem;
      top: 50%;
      transform: translateY(-50%);
    }
  </style>
</head>
<body class="bg-light">
<div class="container mt-5">
  <div class="row justify-content-center">
    <div class="col-md-6 col-lg-5">
      <div class="card shadow">
        <div class="card-header bg-success text-white text-center">
          <h4>Criar Conta</h4>
        </div>
        <div class="card-body">
          <?php if (!empty($_SESSION['erro'])): ?>
            <div class="alert alert-danger"><?= $_SESSION['erro']; unset($_SESSION['erro']); ?></div>
          <?php endif; ?>
          <?php if (!empty($_SESSION['sucesso'])): ?>
            <div class="alert alert-success"><?= $_SESSION['sucesso']; unset($_SESSION['sucesso']); ?></div>
          <?php endif; ?>

          <form method="POST" action="<?= URL_BASE ?>backend/controllers/autenticacao/registro.php">
            <div class="mb-3">
              <label for="nome" class="form-label">Nome</label>
              <input type="text" name="nome" class="form-control" required>
            </div>

            <div class="mb-3">
              <label for="email" class="form-label">E-mail</label>
              <input type="email" name="email" class="form-control" required>
            </div>

            <div class="mb-3 position-relative">
              <label for="senha" class="form-label">Senha</label>
              <input type="password" name="senha" id="senhaRegistro" class="form-control" required>
              <span class="toggle-password" onclick="toggleSenha('senhaRegistro')">👁️</span>
            </div>

            <div class="d-grid">
              <button type="submit" class="btn btn-success">Cadastrar</button>
            </div>
          </form>

          <div class="mt-3 text-center">
            <small>Já tem uma conta? <a href="<?= URL_BASE ?>login/login.php">Entrar</a></small>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
  function toggleSenha(id) {
    const campo = document.getElementById(id);
    campo.type = campo.type === 'password' ? 'text' : 'password';
  }
</script>
</body>
</html>
