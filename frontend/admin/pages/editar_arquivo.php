<?php
// Caminho: frontend/admin/pages/editar_arquivo.php
define('BASE_PATH', dirname(__DIR__, 3)); // /sgbccni
require_once BASE_PATH . '/backend/config/config.php';
require_once BASE_PATH . '/backend/includes/session.php';
require_once BASE_PATH . '/backend/includes/protect_admin.php';
require_once BASE_PATH . '/backend/includes/header.php';

exigir_login('admin');

// 🔍 Dados do arquivo
$id = $_GET['id'] ?? 0;
$stmt = $pdo->prepare("SELECT * FROM arquivos WHERE id = ?");
$stmt->execute([$id]);
$arquivo = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$arquivo) {
  echo "<div class='container py-4'><div class='alert alert-danger'>❌ Arquivo não encontrado.</div></div>";
  require_once BASE_PATH . '/backend/includes/footer.php';
  exit;
}

$categorias = ['apostilas', 'editais', 'imagens', 'outros'];
?>

<div class="container py-4">
  <h4 class="mb-4"><i class="bi bi-pencil-square"></i> Editar Arquivo</h4>

  <?php if (!empty($_SESSION['erro'])): ?>
    <div class="alert alert-danger"><?= $_SESSION['erro']; unset($_SESSION['erro']); ?></div>
  <?php elseif (!empty($_SESSION['sucesso'])): ?>
    <div class="alert alert-success"><?= $_SESSION['sucesso']; unset($_SESSION['sucesso']); ?></div>
  <?php endif; ?>

  <form action="<?= URL_BASE ?>backend/controllers/arquivos/salvar_edicao_arquivo.php" method="POST">
    <input type="hidden" name="id" value="<?= $arquivo['id'] ?>">

    <div class="mb-3">
      <label class="form-label">Nome do Arquivo</label>
      <input type="text" name="nome" class="form-control" value="<?= htmlspecialchars(pathinfo($arquivo['nome'], PATHINFO_FILENAME)) ?>" required>
      <small class="text-muted">A extensão será mantida automaticamente (<?= pathinfo($arquivo['nome'], PATHINFO_EXTENSION) ?>).</small>
    </div>

    <div class="mb-3">
      <label class="form-label">Categoria</label>
      <select name="categoria" class="form-select" required>
        <?php foreach ($categorias as $cat): ?>
          <option value="<?= $cat ?>" <?= $arquivo['categoria'] === $cat ? 'selected' : '' ?>><?= ucfirst($cat) ?></option>
        <?php endforeach; ?>
      </select>
    </div>

    <div class="mt-4 d-flex justify-content-between">
      <a href="gerenciar_arquivos.php" class="btn btn-secondary"><i class="bi bi-arrow-left"></i> Voltar</a>
      <button type="submit" class="btn btn-primary"><i class="bi bi-save"></i> Salvar Alterações</button>
    </div>
  </form>
</div>

<?php require_once BASE_PATH . '/backend/includes/footer.php'; ?>
