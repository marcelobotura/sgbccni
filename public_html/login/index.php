<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/../../config/config.php';
include_once __DIR__ . '/../../includes/header.php';

// 🔐 Se já estiver logado, redireciona para o painel
if (isset($_SESSION['usuario_id'])) {
    if ($_SESSION['usuario_tipo'] === 'admin') {
        header("Location: " . URL_BASE . "admin/");
    } else {
        header("Location: " . URL_BASE . "usuario/");
    }
    exit;
}
?>

<div class="row justify-content-center">
  <div class="col-md-5">
    <h3 class="text-center mb-4">🔐 Login</h3>

    <?php if (isset($_SESSION['erro'])): ?>
      <div class="alert alert-danger"><?= htmlspecialchars($_SESSION['erro']); unset($_SESSION['erro']); ?></div>
    <?php endif; ?>

    <?php if (isset($_SESSION['sucesso'])): ?>
      <div class="alert alert-success"><?= htmlspecialchars($_SESSION['sucesso']); unset($_SESSION['sucesso']); ?></div>
    <?php endif; ?>

    <form action="<?= URL_BASE ?>/../../../controllers/atualizar_perfil.php" method="POST">
      <input type="hidden" name="acao" value="login">

      <div class="mb-3">
        <label for="usuario" class="form-label">Email</label>
        <input type="email" name="usuario" id="usuario" class="form-control" required>
      </div>

      <div class="mb-3">
        <label for="senha" class="form-label">Senha</label>
        <div class="input-group">
          <input type="password" name="senha" id="senha" class="form-control" required>
          <button type="button" class="btn btn-outline-secondary" onclick="toggleSenha(this)">
            <i class="bi bi-eye"></i>
          </button>
        </div>
      </div>

      <button type="submit" class="btn btn-primary w-100">Entrar</button>
      <a href="register.php" class="d-block text-center mt-2">Não tenho conta</a>
    </form>
  </div>
</div>

<script>
function toggleSenha(btn) {
  const input = btn.parentElement.querySelector('input');
  const icon = btn.querySelector('i');
  if (input.type === 'password') {
    input.type = 'text';
    icon.classList.remove('bi-eye');
    icon.classList.add('bi-eye-slash');
  } else {
    input.type = 'password';
    icon.classList.remove('bi-eye-slash');
    icon.classList.add('bi-eye');
  }
}
</script>

<?php include_once __DIR__ . '/../../includes/footer.php'; ?>
