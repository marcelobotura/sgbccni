<?php
define('BASE_PATH', dirname(__DIR__) . '/backend');
require_once BASE_PATH . '/config/config.php';
require_once BASE_PATH . '/includes/session.php';
require_once BASE_PATH . '/includes/header.php';
?>

<!DOCTYPE html>
<html lang="pt-br" data-tema="<?= htmlspecialchars($_COOKIE['modo_tema'] ?? 'claro') ?>">
<head>
  <meta charset="UTF-8">
  <title>Contato - <?= NOME_SISTEMA ?></title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
  <link rel="stylesheet" href="<?= URL_BASE ?>frontend/assets/css/layout.css">
</head>
<body class="bg-light">

<div class="container py-4">
  <!-- Cabeçalho com menu -->
  <header class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="fw-bold text-primary">📬 Contato</h2>
    <nav>
      <a href="index.php" class="btn btn-outline-primary me-2">Início</a>
      <a href="sobre.php" class="btn btn-outline-secondary me-2">Sobre Nós</a>
      <a href="contato.php" class="btn btn-outline-success me-2">Contato</a>
      <a href="sistema.php" class="btn btn-outline-dark me-2">Sistema</a>
      <a href="login.php" class="btn btn-primary"><i class="bi bi-box-arrow-in-right"></i> Entrar</a>
    </nav>
  </header>

  <!-- Formulário de contato -->
  <div class="row justify-content-center">
    <div class="col-md-8">
      <div class="card shadow border-0">
        <div class="card-header bg-primary text-white text-center">
          <h4 class="mb-0">Envie sua Mensagem</h4>
        </div>
        <div class="card-body">

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
              <input 
                type="text" 
                name="nome" 
                id="nome" 
                class="form-control" 
                required 
                placeholder="Seu nome completo"
                value="<?= htmlspecialchars($_SESSION['form_data']['nome'] ?? '') ?>"
              >
            </div>

            <div class="mb-3">
              <label for="email" class="form-label">E-mail:</label>
              <input 
                type="email" 
                name="email" 
                id="email" 
                class="form-control" 
                required 
                placeholder="seu.email@exemplo.com"
                value="<?= htmlspecialchars($_SESSION['form_data']['email'] ?? '') ?>"
              >
            </div>

            <div class="mb-3">
              <label for="celular" class="form-label">Celular (opcional):</label>
              <input 
                type="tel" 
                name="celular" 
                id="celular" 
                class="form-control" 
                placeholder="(99) 99999-9999"
                value="<?= htmlspecialchars($_SESSION['form_data']['celular'] ?? '') ?>"
              >
            </div>

            <div class="mb-3">
              <label for="assunto" class="form-label">Assunto:</label>
              <input 
                type="text" 
                name="assunto" 
                id="assunto" 
                class="form-control" 
                required 
                placeholder="Assunto da mensagem"
                value="<?= htmlspecialchars($_SESSION['form_data']['assunto'] ?? '') ?>"
              >
            </div>

            <div class="mb-3">
              <label for="mensagem" class="form-label">Mensagem:</label>
              <textarea 
                name="mensagem" 
                id="mensagem" 
                rows="5" 
                class="form-control" 
                required 
                placeholder="Escreva sua mensagem aqui"
              ><?= htmlspecialchars($_SESSION['form_data']['mensagem'] ?? '') ?></textarea>
            </div>

            <button type="submit" class="btn btn-primary w-100">📤 Enviar Mensagem</button>
          </form>

        </div>
      </div>
    </div>
  </div>
</div>

<?php 
require_once BASE_PATH . '/includes/footer.php'; 
unset($_SESSION['form_data']);
?>
</body>
</html>
