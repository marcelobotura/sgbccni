<?php
define('BASE_PATH', __DIR__ . '/../app_backend');
require_once BASE_PATH . '/config/config.php';
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <title>Contato - Biblioteca CNI</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
  <div class="container py-5">
    <div class="row justify-content-center">
      <div class="col-md-6">
        <div class="card shadow">
          <div class="card-header bg-info text-white">
            <h4 class="mb-0">Fale Conosco</h4>
          </div>
          <div class="card-body">
            <form action="enviar_contato.php" method="post">
              <div class="mb-3">
                <label>Seu nome:</label>
                <input type="text" name="nome" class="form-control" required>
              </div>
              <div class="mb-3">
                <label>Seu e-mail:</label>
                <input type="email" name="email" class="form-control" required>
              </div>
              <div class="mb-3">
                <label>Mensagem:</label>
                <textarea name="mensagem" class="form-control" rows="5" required></textarea>
              </div>
              <button type="submit" class="btn btn-info w-100">Enviar Mensagem</button>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</body>
</html>
