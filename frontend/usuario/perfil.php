<?php
define('BASE_PATH', dirname(__DIR__) . '/../backend');
require_once BASE_PATH . '/config/config.php';
require_once BASE_PATH . '/includes/session.php';
include_once BASE_PATH . '/includes/header.php';

exigir_login('usuario');

$nome = htmlspecialchars($_SESSION['usuario_nome']);
$email = htmlspecialchars($_SESSION['usuario_email'] ?? '');
$foto = $_SESSION['usuario_foto'] ?? null;
?>

<div class="container py-4">
  <div class="row justify-content-center">
    <div class="col-md-8">
      <div class="card shadow-lg">
        <div class="card-header bg-info text-white d-flex justify-content-between align-items-center">
          <h4 class="mb-0"><i class="bi bi-person-lines-fill"></i> Meu Perfil</h4>
          <a href="index.php" class="btn btn-sm btn-light">← Voltar</a>
        </div>
        <div class="card-body">

          <?php if (isset($_SESSION['sucesso'])): ?>
            <div class="alert alert-success"><?= $_SESSION['sucesso']; unset($_SESSION['sucesso']); ?></div>
          <?php elseif (isset($_SESSION['erro'])): ?>
            <div class="alert alert-danger"><?= $_SESSION['erro']; unset($_SESSION['erro']); ?></div>
          <?php endif; ?>

          <form method="POST" action="salvar_perfil.php" enctype="multipart/form-data">
            <div class="mb-3 text-center">
              <?php if ($foto): ?>
                <img src="<?= URL_BASE . 'uploads/usuarios/' . htmlspecialchars($foto) ?>" alt="Foto de perfil" class="rounded-circle shadow" style="width: 120px; height: 120px; object-fit: cover;">
              <?php else: ?>
                <div class="bg-light rounded-circle shadow d-inline-flex align-items-center justify-content-center" style="width: 120px; height: 120px;">
                  <i class="bi bi-person-circle" style="font-size: 2.5rem;"></i>
                </div>
              <?php endif; ?>
            </div>

            <div class="mb-3">
              <label class="form-label">Nome:</label>
              <input type="text" name="nome" class="form-control" value="<?= $nome ?>" required>
            </div>

            <div class="mb-3">
              <label class="form-label">Email:</label>
              <input type="email" name="email" class="form-control" value="<?= $email ?>" required>
            </div>

            <div class="mb-3">
              <label class="form-label">Nova senha (opcional):</label>
              <input type="password" name="nova_senha" class="form-control">
            </div>

            <div class="mb-3">
              <label class="form-label">Foto de perfil (opcional):</label>
              <input type="file" name="foto" class="form-control" accept="image/*">
            </div>

            <div class="d-flex justify-content-between">
              <button type="submit" class="btn btn-primary">💾 Salvar alterações</button>
              <a href="excluir_conta.php" class="btn btn-outline-danger" onclick="return confirm('Tem certeza que deseja excluir sua conta?');">🗑️ Excluir conta</a>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>

<?php include_once BASE_PATH . '/includes/footer.php'; ?>
