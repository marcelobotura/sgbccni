<?php
session_start();
define('BASE_PATH', dirname(__DIR__, 2) . '/backend');
require_once BASE_PATH . '/config/config.php';
require_once BASE_PATH . '/includes/session.php';
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <title>Cadastro - Biblioteca CNI</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
  <style>
    .toggle-password {
      position: absolute;
      top: 38px;
      right: 10px;
      cursor: pointer;
      color: #6c757d;
    }
  </style>
</head>
<body class="bg-light">

<div class="container py-5">
  <div class="row justify-content-center">
    <div class="col-md-5">
      <div class="card shadow">
        <div class="card-header bg-success text-white text-center">
          <h4 class="mb-0">📝 Criar Conta</h4>
        </div>
        <div class="card-body">

          <?php if (!empty($_SESSION['erro'])): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($_SESSION['erro']); unset($_SESSION['erro']); ?></div>
          <?php endif; ?>

          <?php if (!empty($_SESSION['sucesso'])): ?>
            <div class="alert alert-success"><?= htmlspecialchars($_SESSION['sucesso']); unset($_SESSION['sucesso']); ?></div>
          <?php endif; ?>

          <form method="POST" action="register_valida.php">
            <div class="mb-3">
              <label for="nome" class="form-label">Nome completo:</label>
              <input type="text" name="nome" id="nome" class="form-control" required>
            </div>

            <div class="mb-3">
              <label for="email" class="form-label">E-mail:</label>
              <input type="email" name="email" id="email" class="form-control" required>
            </div>

            <div class="mb-3 position-relative">
              <label for="senha" class="form-label">Senha:</label>
              <input type="password" name="senha" id="senha" class="form-control" required>
              <i class="bi bi-eye toggle-password" onclick="toggleSenha(this)"></i>
            </div>

            <button type="submit" class="btn btn-success w-100">Cadastrar</button>
          </form>

          <div class="mt-3 text-center">
            <a href="login.php">Já tenho uma conta</a>
          </div>

        </div>
      </div>
    </div>
  </div>
</div>

<script>
  function toggleSenha(icon) {
    const input = document.getElementById('senha');
    const isPassword = input.type === 'password';
    input.type = isPassword ? 'text' : 'password';
    icon.classList.toggle('bi-eye');
    icon.classList.toggle('bi-eye-slash');
  }
</script>

</body>
</html>
