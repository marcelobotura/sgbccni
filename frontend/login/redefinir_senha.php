<?php
define('BASE_PATH', dirname(__DIR__) . '/backend');
require_once BASE_PATH . '/config/config.php';
require_once BASE_PATH . '/includes/session.php';
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <title>Redefinir Senha - Biblioteca CNI</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container py-5">
  <div class="row justify-content-center">
    <div class="col-md-6 col-lg-5">
      <div class="card shadow">
        <div class="card-header bg-warning text-dark text-center">
          <h5 class="mb-0">🔐 Recuperar Senha</h5>
        </div>
        <div class="card-body">

          <?php if (isset($_SESSION['erro'])): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($_SESSION['erro']) ?></div>
            <?php unset($_SESSION['erro']); ?>
          <?php endif; ?>

          <?php if (isset($_SESSION['sucesso'])): ?>
            <div class="alert alert-success"><?= htmlspecialchars($_SESSION['sucesso']) ?></div>
            <?php unset($_SESSION['sucesso']); ?>
          <?php endif; ?>

          <form method="POST" action="salvar_nova_senha.php">
            <div class="mb-3">
              <label for="email" class="form-label">Seu e-mail:</label>
              <input type="email" name="email" id="email" class="form-control" required>
            </div>

            <div class="mb-3">
              <label for="nova_senha" class="form-label">Nova senha:</label>
              <input type="password" name="nova_senha" id="nova_senha" class="form-control" required>
            </div>

            <button type="submit" class="btn btn-warning w-100">Redefinir Senha</button>
          </form>

          <div class="mt-3 text-center">
            <a href="login.php">Voltar ao login</a>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

</body>
</html>
