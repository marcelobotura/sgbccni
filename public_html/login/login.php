<?php
session_start();
define('BASE_PATH', dirname(__DIR__) . '/../app_backend');
require_once BASE_PATH . '/config/config.php';
require_once BASE_PATH . '/includes/session.php';

$erro = $_SESSION['erro'] ?? null;
unset($_SESSION['erro']);
?>

<?php include_once BASE_PATH . '/includes/header.php'; ?>

<div class="container py-5 d-flex justify-content-center">
  <div class="card shadow p-4" style="width: 100%; max-width: 400px;">
    <h3 class="text-center mb-4 text-primary">🔐 Login Biblioteca CNI</h3>

    <?php if ($erro): ?>
      <div class="alert alert-danger"><?= htmlspecialchars($erro) ?></div>
    <?php endif; ?>

    <form action="login_valida.php" method="POST">
      <div class="mb-3">
        <label>Email:</label>
        <input type="email" name="email" class="form-control" required placeholder="Digite seu email">
      </div>

      <div class="mb-3 position-relative">
        <label>Senha:</label>
        <input type="password" name="senha" id="senha" class="form-control" required placeholder="Digite sua senha">
        <button type="button" onclick="verSenha()" style="position: absolute; right: 10px; top: 34px; border: none; background: transparent;">
          👁️
        </button>
      </div>

      <button type="submit" class="btn btn-primary w-100">Entrar</button>
    </form>

    <div class="text-center mt-3">
      <a href="#">Esqueceu sua senha?</a> | <a href="register.php">Criar nova conta</a>
    </div>
  </div>
</div>

<script>
function verSenha() {
  const input = document.getElementById('senha');
  input.type = input.type === 'password' ? 'text' : 'password';
}
</script>

<?php include_once BASE_PATH . '/includes/footer.php'; ?>
