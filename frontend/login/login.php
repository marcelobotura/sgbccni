<?php
session_start();
require_once __DIR__ . '/../../backend/config/config.php';

// Redireciona se já estiver logado
if (isset($_SESSION['usuario_id'])) {
  $destino = $_SESSION['usuario_tipo'] === 'admin' ? '../admin/dashboard.php' : '../usuario/index.php';
  header("Location: $destino");
  exit;
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Login - <?= NOME_SISTEMA ?></title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
  <link rel="stylesheet" href="<?= URL_BASE ?>frontend/assets/css/admin.css">
</head>
<body class="bg-light">

<div class="container d-flex justify-content-center align-items-center min-vh-100">
  <div class="card shadow p-4" style="max-width: 400px; width: 100%;">
    <h4 class="text-center mb-4">🔐 Login</h4>

    <?php if (!empty($_SESSION['erro'])): ?>
      <div class="alert alert-danger"><?= htmlspecialchars($_SESSION['erro']); unset($_SESSION['erro']); ?></div>
    <?php endif; ?>

    <form method="POST" action="login_valida.php">
      <input type="hidden" name="acao" value="login">

      <div class="mb-3">
        <label for="email" class="form-label">E-mail:</label>
        <input type="email" name="email" id="email" class="form-control" required>
      </div>

      <div class="mb-3">
        <label for="senha" class="form-label">Senha:</label>
        <div class="input-group">
          <input type="password" name="senha" id="senha" class="form-control" required>
          <span class="input-group-text bg-white" onclick="toggleSenha(this)">
            <i class="bi bi-eye" id="iconeSenha"></i>
          </span>
        </div>
      </div>

      <button type="submit" class="btn btn-primary w-100">Entrar</button>
    </form>

    <div class="text-center mt-3">
      <a href="register.php">Criar uma conta</a>
    </div>
  </div>
</div>

<script>
function toggleSenha(el) {
  const senha = document.getElementById('senha');
  const icone = document.getElementById('iconeSenha');
  if (senha.type === 'password') {
    senha.type = 'text';
    icone.classList.remove('bi-eye');
    icone.classList.add('bi-eye-slash');
  } else {
    senha.type = 'password';
    icone.classList.remove('bi-eye-slash');
    icone.classList.add('bi-eye');
  }
}
</script>

</body>
</html>
