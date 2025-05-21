<?php
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../includes/session.php';
include_once __DIR__ . '/../../includes/header.php';
?>

<div class="row justify-content-center">
  <div class="col-md-6">
    <h3 class="text-center mb-4">👤 Criar Conta</h3>

    <?php if (isset($_SESSION['erro'])): ?>
      <div class="alert alert-danger"><?= htmlspecialchars($_SESSION['erro']); unset($_SESSION['erro']); ?></div>
    <?php endif; ?>

    <?php if (isset($_SESSION['sucesso'])): ?>
      <div class="alert alert-success"><?= htmlspecialchars($_SESSION['sucesso']); unset($_SESSION['sucesso']); ?></div>
    <?php endif; ?>

    <form action="<?= URL_BASE ?>controllers/auth.php" method="POST" enctype="multipart/form-data">
      <input type="hidden" name="acao" value="register">
      <input type="hidden" name="tipo" value="usuario">

      <div class="mb-3">
        <label for="nome" class="form-label">Nome completo</label>
        <input type="text" name="nome" id="nome" class="form-control" required>
      </div>

      <div class="mb-3">
        <label for="email" class="form-label">Email</label>
        <input type="email" name="email" id="email" class="form-control" required>
      </div>

      <div class="mb-3">
        <label for="senha" class="form-label">Senha</label>
        <div class="input-group">
          <input type="password" name="senha" class="form-control" id="senha" required>
          <button type="button" class="btn btn-outline-secondary" onclick="toggleSenha(this)">
            <i class="bi bi-eye"></i>
          </button>
        </div>
      </div>

      <div class="mb-3">
        <label for="foto_perfil" class="form-label">Foto de perfil (opcional)</label>
        <input type="file" name="foto_perfil" id="foto_perfil" class="form-control">
      </div>

      <div class="row">
        <div class="col-md-6 mb-3">
          <label for="data_nascimento" class="form-label">Data de nascimento</label>
          <input type="date" name="data_nascimento" class="form-control">
        </div>
        <div class="col-md-6 mb-3">
          <label for="genero" class="form-label">Gênero</label>
          <select name="genero" class="form-select">
            <option value="">Selecione</option>
            <option value="Masculino">Masculino</option>
            <option value="Feminino">Feminino</option>
            <option value="Outro">Outro</option>
          </select>
        </div>
      </div>

      <div class="mb-3">
        <label for="cep" class="form-label">CEP</label>
        <input type="text" name="cep" class="form-control">
      </div>

      <div class="mb-3">
        <label for="endereco" class="form-label">Endereço</label>
        <input type="text" name="endereco" class="form-control">
      </div>

      <div class="row">
        <div class="col-md-6 mb-3">
          <label for="cidade" class="form-label">Cidade</label>
          <input type="text" name="cidade" class="form-control">
        </div>
        <div class="col-md-6 mb-3">
          <label for="estado" class="form-label">Estado</label>
          <input type="text" name="estado" class="form-control">
        </div>
      </div>

      <button type="submit" class="btn btn-success w-100">Cadastrar</button>
      <a href="index.php" class="d-block text-center mt-2 text-light">Já tenho conta</a>
    </form>
  </div>
</div>

<script>
function toggleSenha(btn) {
  const input = btn.parentElement.querySelector('input');
  const icon = btn.querySelector('i');
  if (input.type === 'password') {
    input.type = 'text';
    icon.classList.replace('bi-eye', 'bi-eye-slash');
  } else {
    input.type = 'password';
    icon.classList.replace('bi-eye-slash', 'bi-eye');
  }
}
</script>

<?php include_once __DIR__ . '/../../includes/footer.php'; ?>
