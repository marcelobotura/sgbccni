<?php
session_start();
require_once __DIR__ . '/../../backend/config/config.php';
require_once __DIR__ . '/../../backend/includes/session.php';

// Redireciona se já estiver logado
if (isset($_SESSION['usuario_id']) && $_SESSION['usuario_tipo'] === 'admin') {
    header("Location: ../../frontend/admin/dashboard.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Login Administrativo - <?= NOME_SISTEMA ?></title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="<?= URL_BASE ?>frontend/assets/css/login.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
</head>
<body class="bg-light">

<div class="login-box">
  <h2 class="text-center mb-4">🔐 Login Administrativo</h2>

  <?php if (!empty($_SESSION['erro'])): ?>
    <div class="alert alert-danger"><?= htmlspecialchars($_SESSION['erro']); unset($_SESSION['erro']); ?></div>
  <?php endif; ?>

  <form action="login_valida.php" method="POST">
    <input type="hidden" name="acao" value="login">

    <div class="mb-3">
      <label for="email" class="form-label">E-mail</label>
      <input type="email" name="email" id="email" class="form-control" required>
    </div>

    <div class="mb-3 position-relative">
      <label for="senha" class="form-label">Senha</label>
      <div class="input-group">
        <input type="password" name="senha" id="senha" class="form-control" required>
        <span class="input-group-text bg-white">
          <i class="bi bi-eye toggle-password" onclick="toggleSenha(this)"></i>
        </span>
      </div>
    </div>

    <button type="submit" class="btn btn-dark w-100">Entrar</button>
  </form>
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
