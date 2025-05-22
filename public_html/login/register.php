<?php
session_start();
require_once '../config/config.php';
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <title>Cadastro - <?= defined('NOME_SISTEMA') ? NOME_SISTEMA : 'Sistema' ?></title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  
  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Bootstrap Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body class="bg-dark text-white">

<div class="container mt-5">
  <div class="row justify-content-center">
    <div class="col-md-4">
      <h3 class="text-center mb-4">👤 Criar Conta</h3>

      <?php if (isset($_SESSION['erro'])): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($_SESSION['erro']); unset($_SESSION['erro']); ?></div>
      <?php endif; ?>

      <?php if (isset($_SESSION['sucesso'])): ?>
        <div class="alert alert-success"><?= htmlspecialchars($_SESSION['sucesso']); unset($_SESSION['sucesso']); ?></div>
      <?php endif; ?>

      <form action="../controllers/auth.php" method="POST">
        <input type="hidden" name="acao" value="register">

        <div class="mb-3">
          <label for="nome" class="form-label">Nome completo</label>
          <input type="text" name="nome" id="nome" class="form-control" required>
        </div>

        <div class="mb-3">
          <label for="email" class="form-label">Email</label>
          <input type="email" name="email" id="email" class="form-control" required>
        </div>

        <div class="mb-3">
          <label for="senha" class="form-label">Senha</label>
          <div class="input-group">
            <input type="password" name="senha" class="form-control" id="senha" required>
            <button type="button" class="btn btn-outline-secondary" onclick="toggleSenha(this)">
              <i class="bi bi-eye"></i>
            </button>
          </div>
        </div>

        <button type="submit" class="btn btn-success w-100">Cadastrar</button>
        <a href="login.php" class="d-block text-center mt-2 text-light">Já tenho uma conta</a>
      </form>
    </div>
  </div>
</div>

<script>
function toggleSenha(button) {
  const input = document.getElementById('senha');
  const icon = button.querySelector('i');
  if (input.type === 'password') {
    input.type = 'text';
    icon.classList.replace('bi-eye', 'bi-eye-slash');
  } else {
    input.type = 'password';
    icon.classList.replace('bi-eye-slash', 'bi-eye');
  }
}
</script>

</body>
</html>
