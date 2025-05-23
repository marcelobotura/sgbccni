<?php
define('BASE_PATH', dirname(__DIR__) . '/../app_backend');
require_once BASE_PATH . '/config/config.php';
require_once BASE_PATH . '/includes/session.php';
include_once BASE_PATH . '/includes/header.php';

exigir_login('usuario');
?>

<div class="container py-4">
  <div class="row">
    <div class="col-md-8 offset-md-2">
      <div class="card shadow">
        <div class="card-header bg-info text-white">
          <h4 class="mb-0"><i class="bi bi-person-lines-fill"></i> Meu Perfil</h4>
        </div>
        <div class="card-body">
          <?php if (isset($_SESSION['sucesso'])): ?>
            <div class="alert alert-success"><?= $_SESSION['sucesso']; unset($_SESSION['sucesso']); ?></div>
          <?php elseif (isset($_SESSION['erro'])): ?>
            <div class="alert alert-danger"><?= $_SESSION['erro']; unset($_SESSION['erro']); ?></div>
          <?php endif; ?>

          <?php if (!empty($_SESSION['usuario_foto'])): ?>
            <div class="text-center mb-3">
              <img src="<?= URL_BASE ?>uploads/<?= htmlspecialchars($_SESSION['usuario_foto']) ?>" class="rounded-circle" width="120" height="120" alt="Foto de perfil">
            </div>
          <?php endif; ?>

          <form method="POST" action="salvar_perfil.php" enctype="multipart/form-data">
            <div class="mb-3">
              <label>Nome:</label>
              <input type="text" name="nome" class="form-control" value="<?= htmlspecialchars($_SESSION['usuario_nome']) ?>" required>
            </div>
            <div class="mb-3">
              <label>Email:</label>
              <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($_SESSION['usuario_email'] ?? '') ?>" required>
            </div>
            <div class="mb-3">
              <label>Nova senha (opcional):</label>
              <input type="password" name="nova_senha" class="form-control">
            </div>
            <div class="mb-3">
              <label>Foto de perfil (opcional):</label>
              <input type="file" name="foto" class="form-control" onchange="previewImagem(this)">
              <img id="preview" src="#" class="mt-3 d-none rounded-circle" width="100" alt="Preview">
            </div>
            <button type="submit" class="btn btn-primary">Salvar alterações</button>
            <a href="excluir_conta.php" class="btn btn-outline-danger float-end" onclick="return confirm('Tem certeza que deseja excluir sua conta?');">Excluir conta</a>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
  function previewImagem(input) {
    const file = input.files[0];
    if (file) {
      const preview = document.getElementById('preview');
      preview.src = URL.createObjectURL(file);
      preview.classList.remove('d-none');
    }
  }
</script>

<?php include_once BASE_PATH . '/includes/footer.php'; ?>
