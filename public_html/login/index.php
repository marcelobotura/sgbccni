<?php
define('BASE_PATH', dirname(__DIR__) . '/backend');
require_once BASE_PATH . '/config/config.php';
require_once BASE_PATH . '/includes/session.php';
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <title>Login - Biblioteca CNI</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    .toggle-password {
      cursor: pointer;
      position: absolute;
      right: 10px;
      top: 38px;
      z-index: 2;
    }
  </style>
</head>
<body class="bg-light">

  <div class="container py-5">
    <div class="row justify-content-center">
      <div class="col-md-5">
        <div class="card shadow">
          <div class="card-header bg-primary text-white text-center">
            <h4 class="mb-0">🔐 Login Biblioteca CNI</h4>
          </div>
          <div class="card-body">

            <?php if (isset($_SESSION['erro'])): ?>
              <div class="alert alert-danger"><?= htmlspecialchars($_SESSION['erro']) ?></div>
              <?php unset($_SESSION['erro']); ?>
            <?php endif; ?>

            <form method="POST" action="processa_login.php">
              <div class="mb-3">
                <label>Email:</label>
                <input type="text" name="usuario" class="form-control" required>
              </div>

              <div class="mb-3 position-relative">
                <label>Senha:</label>
                <input type="password" name="senha" id="senha" class="form-control" required>
                <span class="toggle-password" onclick="toggleSenha()">👁️</span>
              </div>

              <button type="submit" class="btn btn-primary w-100">Entrar</button>
            </form>

            <div class="mt-3 text-center">
              <a href="redefinir_senha.php">Esqueceu sua senha?</a> |
              <a href="register.php">Criar nova conta</a>
            </div>

          </div>
        </div>
      </div>
    </div>
  </div>

  <script>
    function toggleSenha() {
      const senha = document.getElementById('senha');
      senha.type = senha.type === 'password' ? 'text' : 'password';
    }
  </script>
</body>
</html>
