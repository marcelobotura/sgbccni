<?php
require_once __DIR__ . '/../../config/config.php';
include_once __DIR__ . '/../../includes/header.php';

if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_tipo'] !== 'usuario') {
    header("Location: " . URL_BASE . "login/");
    exit;
}

// Buscar os dados do usuário logado
$id = $_SESSION['usuario_id'];
$stmt = $conn->prepare("SELECT nome, email, imagem_perfil, data_nascimento, genero, cep, endereco, cidade, estado FROM usuarios WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$stmt->bind_result($nome, $email, $foto, $nascimento, $genero, $cep, $endereco, $cidade, $estado);
$stmt->fetch();
$stmt->close();
?>

<div class="text-end mb-3">
  <a href="<?= URL_BASE ?>usuario/" class="btn btn-outline-secondary btn-sm">
    <i class="bi bi-arrow-left-circle"></i> Voltar
  </a>
</div>

<h3 class="mb-4 text-center"><i class="bi bi-person-lines-fill"></i> Meu Perfil</h3>

<div class="row justify-content-center">
  <div class="col-md-6">
    <form action="<?= URL_BASE ?>controllers/atualizar_perfil.php" method="POST" enctype="multipart/form-data">
      <input type="hidden" name="id" value="<?= $id ?>">

      <div class="text-center mb-3">
        <?php if ($foto): ?>
          <img src="<?= URL_BASE . $foto ?>" class="rounded-circle" width="120" height="120" alt="Perfil">
        <?php else: ?>
          <i class="bi bi-person-circle fs-1 text-muted"></i>
        <?php endif; ?>
      </div>

      <div class="mb-3">
        <label class="form-label">Alterar foto de perfil</label>
        <input type="file" name="foto_perfil" class="form-control">
      </div>

      <div class="mb-3">
        <label class="form-label">Nome</label>
        <input type="text" name="nome" class="form-control" value="<?= htmlspecialchars($nome) ?>" required>
      </div>

      <div class="mb-3">
        <label class="form-label">Email</label>
        <input type="email" class="form-control" value="<?= htmlspecialchars($email) ?>" disabled>
      </div>

      <div class="row">
        <div class="col-md-6 mb-3">
          <label class="form-label">Nascimento</label>
          <input type="date" name="data_nascimento" class="form-control" value="<?= $nascimento ?>">
        </div>
        <div class="col-md-6 mb-3">
          <label class="form-label">Gênero</label>
          <select name="genero" class="form-select">
            <option value="">Selecione</option>
            <option value="Masculino" <?= $genero == 'Masculino' ? 'selected' : '' ?>>Masculino</option>
            <option value="Feminino" <?= $genero == 'Feminino' ? 'selected' : '' ?>>Feminino</option>
            <option value="Outro" <?= $genero == 'Outro' ? 'selected' : '' ?>>Outro</option>
          </select>
        </div>
      </div>

      <div class="mb-3">
        <label class="form-label">CEP</label>
        <input type="text" name="cep" class="form-control" value="<?= $cep ?>">
      </div>

      <div class="mb-3">
        <label class="form-label">Endereço</label>
        <input type="text" name="endereco" class="form-control" value="<?= $endereco ?>">
      </div>

      <div class="row">
        <div class="col-md-6 mb-3">
          <label class="form-label">Cidade</label>
          <input type="text" name="cidade" class="form-control" value="<?= $cidade ?>">
        </div>
        <div class="col-md-6 mb-3">
          <label class="form-label">Estado</label>
          <input type="text" name="estado" class="form-control" value="<?= $estado ?>">
        </div>
      </div>

      <button type="submit" class="btn btn-primary w-100">Salvar alterações</button>
    </form>
  </div>
</div>

<?php include_once __DIR__ . '/../../includes/footer.php'; ?>
