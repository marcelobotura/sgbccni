<?php
define('BASE_PATH', dirname(__DIR__) . '/../backend');
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
              <input type="file" name="foto" class="form-control">
            </div>
            <button type="submit" class="btn btn-primary">Salvar alterações</button>
            <a href="excluir_conta.php" class="btn btn-outline-danger float-end" onclick="return confirm('Tem certeza que deseja excluir sua conta?');">Excluir conta</a>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>

<?php include_once BASE_PATH . '/includes/footer.php'; ?>
