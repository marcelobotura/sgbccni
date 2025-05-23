<?php
define('BASE_PATH', dirname(__FILE__) . '/../backend');
require_once BASE_PATH . '/config/config.php';
require_once BASE_PATH . '/includes/session.php';
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <title>Contato - Biblioteca CNI</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container py-5">
  <div class="row justify-content-center">
    <div class="col-md-8">
      <h3 class="mb-4 text-center">📬 Entre em Contato</h3>

      <?php if (isset($_SESSION['sucesso'])): ?>
        <div class="alert alert-success text-center"><?= htmlspecialchars($_SESSION['sucesso']) ?></div>
        <?php unset($_SESSION['sucesso']); ?>
      <?php elseif (isset($_SESSION['erro'])): ?>
        <div class="alert alert-danger text-center"><?= htmlspecialchars($_SESSION['erro']) ?></div>
        <?php unset($_SESSION['erro']); ?>
      <?php endif; ?>

      <form method="POST" action="enviar_contato.php">
        <div class="mb-3">
          <label for="nome" class="form-label">Nome:</label>
          <input type="text" name="nome" id="nome" class="form-control" required>
        </div>

        <div class="mb-3">
          <label for="email" class="form-label">E-mail:</label>
          <input type="email" name="email" id="email" class="form-control" required>
        </div>

        <div class="mb-3">
          <label for="mensagem" class="form-label">Mensagem:</label>
          <textarea name="mensagem" id="mensagem" rows="5" class="form-control" required></textarea>
        </div>

        <button type="submit" class="btn btn-primary w-100">Enviar Mensagem</button>
      </form>
    </div>
  </div>
</div>

</body>
</html>
