<?php
require_once '../includes/config.php';
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <title>Login - <?= NOME_SISTEMA ?></title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-dark text-white">

<div class="container mt-5">
  <div class="row justify-content-center">
    <div class="col-md-4">
      <h3 class="text-center mb-4">🔐 Login</h3>

      <?php if (isset($_SESSION['erro'])): ?>
        <div class="alert alert-danger"><?= $_SESSION['erro']; unset($_SESSION['erro']); ?></div>
      <?php endif; ?>

      <form action="../controllers/auth.php" method="POST">
        <input type="hidden" name="acao" value="login">
        <div class="mb-3">
          <label>Email</label>
          <input type="email" name="email" class="form-control" required>
        </div>
        <div class="mb-3">
          <label>Senha</label>
          <div class="input-group">
            <input type="password" name="senha" class="form-control" id="senha" required>
            <button type="button" class="btn btn-outline-secondary" onclick="toggleSenha()">👁</button>
          </div>
        </div>
        <button type="submit" class="btn btn-primary w-100">Entrar</button>
        <a href="register.php" class="d-block text-center mt-2 text-light">Criar Conta</a>
      </form>
    </div>
  </div>
</div>

<script>
function toggleSenha() {
  var input = document.getElementById('senha');
  input.type = input.type === 'password' ? 'text' : 'password';
}
</script>
</body>
</html>
