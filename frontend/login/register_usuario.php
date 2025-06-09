<?php 
session_start();
define('BASE_PATH', dirname(__DIR__, 2) . '/backend');
require_once BASE_PATH . '/config/config.php';
require_once BASE_PATH . '/includes/session.php';
?>
<!DOCTYPE html>
<html lang="pt-br" data-tema="<?= htmlspecialchars($_COOKIE['modo_tema'] ?? 'claro') ?>">
<head>
  <meta charset="UTF-8">
  <title>Cadastro - <?= NOME_SISTEMA ?></title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
  <link rel="stylesheet" href="<?= URL_BASE ?>frontend/assets/css/login.css">
  <style>
    .toggle-password {
      position: absolute;
      right: 1rem;
      top: 2.8rem; /* Ajuste conforme necessário */
      cursor: pointer;
      z-index: 2;
    }
  </style>
</head>
<body class="bg-light">

<div class="container py-5">
  <div class="row justify-content-center">
    <div class="col-md-5">
      <div class="card shadow-sm border-0">
        <div class="card-body p-4">
          <h4 class="mb-4 text-center">👋 Crie sua Conta Grátis</h4>

          <?php include_once BASE_PATH . '/includes/alerta.php'; // Inclui o sistema de alertas ?>

          <form method="POST" action="register_valida.php">
            <div class="mb-3">
              <label for="nome" class="form-label">Nome completo:</label>
              <input type="text" name="nome" id="nome" class="form-control" required value="<?= htmlspecialchars($_SESSION['form_data']['nome'] ?? '') ?>">
            </div>

            <div class="mb-3">
              <label for="email" class="form-label">E-mail:</label>
              <input type="email" name="email" id="email" class="form-control" required value="<?= htmlspecialchars($_SESSION['form_data']['email'] ?? '') ?>">
            </div>

            <div class="mb-3 position-relative">
              <label for="senha" class="form-label">Senha:</label>
              <input type="password" name="senha" id="senha" class="form-control" required>
              <i class="bi bi-eye toggle-password" onclick="toggleSenha(this, 'senha')"></i>
            </div>

            <div class="mb-3 position-relative"> <label for="senha2" class="form-label">Confirmar Senha:</label>
              <input type="password" name="senha2" id="senha2" class="form-control" required>
              <i class="bi bi-eye toggle-password" onclick="toggleSenha(this, 'senha2')"></i>
            </div>

            <button type="submit" class="btn btn-success w-100 mt-3">Cadastrar</button>
          </form>

          <div class="mt-3 text-center">
            <a href="<?= URL_BASE ?>login.php">🔐 Já tenho uma conta</a>
          </div>

        </div>
      </div>
    </div>
  </div>
</div>

<script>
  function toggleSenha(icon, inputId) {
    const input = document.getElementById(inputId);
    const isPassword = input.type === 'password';
    input.type = isPassword ? 'text' : 'password';
    icon.classList.toggle('bi-eye');
    icon.classList.toggle('bi-eye-slash');
  }

  // Limpar dados do formulário da sessão após exibição (opcional, se quiser persistir erro mas não dados)
  <?php unset($_SESSION['form_data']); ?>
</script>

<?php 
// Inclui o rodapé da página
// Nota: O footer.php deve estar na pasta 'backend/includes'
include_once BASE_PATH . '/includes/footer.php'; 
?>