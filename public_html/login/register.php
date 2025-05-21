<?php
define('BASE_PATH', dirname(__DIR__) . '/../app_backend');
require_once BASE_PATH . '/config/config.php';
require_once BASE_PATH . '/includes/session.php';
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <title>Cadastro - Biblioteca CNI</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container py-5">
  <div class="row justify-content-center">
    <div class="col-md-6">
      <div class="card shadow">
        <div class="card-header bg-success text-white">
          <h4 class="mb-0">📘 Criar nova conta</h4>
        </div>
        <div class="card-body">
          <form method="POST" action="salvar_usuario.php">
            <div class="mb-3">
              <label>Nome:</label>
              <input type="text" name="nome" class="form-control" required>
            </div>
            <div class="mb-3">
              <label>Email:</label>
              <input type="email" name="email" class="form-control" required>
            </div>
            <div class="mb-3 position-relative">
              <label>Senha:</label>
              <input type="password" name="senha" id="senha" class="form-control" required>
              <span class="position-absolute end-0 top-50 translate-middle-y pe-3" onclick="toggleSenha()" style="cursor: pointer;">👁️</span>
            </div>
            <button type="submit" class="btn btn-success w-100">Registrar</button>
          </form>
          <div class="mt-3 text-center">
            <a href="index.php">← Voltar para o login</a>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
  function toggleSenha() {
    const input = document.getElementById('senha');
    input.type = input.type === 'password' ? 'text' : 'password';
  }
</script>
</body>
</html>
