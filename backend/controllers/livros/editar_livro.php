<?php
require_once __DIR__ . '/../../../backend/includes/header.php';
require_once __DIR__ . '/../../../backend/includes/menu.php';
require_once __DIR__ . '/../../../backend/config/config.php';
exigir_login('admin');

$id = $_GET['id'] ?? null;
if (!$id) {
  $_SESSION['erro'] = 'Livro não especificado.';
  header("Location: listar_livros.php");
  exit;
}

// Busca dados do livro
$stmt = $conn->prepare("SELECT * FROM livros WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$livro = $result->fetch_assoc();

if (!$livro) {
  $_SESSION['erro'] = 'Livro não encontrado.';
  header("Location: listar_livros.php");
  exit;
}
?>

<div class="container py-4">
  <h2 class="mb-4">✏️ Editar Livro</h2>

  <?php if (!empty($_SESSION['erro'])): ?>
    <div class="alert alert-danger"><?= htmlspecialchars($_SESSION['erro']); unset($_SESSION['erro']); ?></div>
  <?php elseif (!empty($_SESSION['sucesso'])): ?>
    <div class="alert alert-success"><?= htmlspecialchars($_SESSION['sucesso']); unset($_SESSION['sucesso']); ?></div>
  <?php endif; ?>

  <form action="<?= URL_BASE ?>backend/controllers/livros/atualizar_livro.php" method="POST" enctype="multipart/form-data">
    <input type="hidden" name="id" value="<?= $livro['id'] ?>">

    <div class="row mb-3">
      <div class="col-md-8">
        <label for="titulo" class="form-label">Título *</label>
        <input type="text" name="titulo" id="titulo" class="form-control" value="<?= htmlspecialchars($livro['titulo']) ?>" required>
      </div>
      <div class="col-md-4">
        <label for="isbn" class="form-label">ISBN *</label>
        <input type="text" name="isbn" id="isbn" class="form-control" value="<?= htmlspecialchars($livro['isbn']) ?>" required>
      </div>
    </div>

    <div class="mb-3">
      <label for="descricao" class="form-label">Descrição</label>
      <textarea name="descricao" id="descricao" rows="4" class="form-control"><?= htmlspecialchars($livro['descricao']) ?></textarea>
    </div>

    <div class="row mb-3">
      <div class="col-md-4">
        <label for="tipo" class="form-label">Tipo</label>
        <select name="tipo" id="tipo" class="form-select">
          <option value="físico" <?= $livro['tipo'] === 'físico' ? 'selected' : '' ?>>Físico</option>
          <option value="digital" <?= $livro['tipo'] === 'digital' ? 'selected' : '' ?>>Digital</option>
        </select>
      </div>
      <div class="col-md-4">
        <label for="formato" class="form-label">Formato</label>
        <select name="formato" id="formato" class="form-select">
          <option value="PDF" <?= $livro['formato'] === 'PDF' ? 'selected' : '' ?>>PDF</option>
          <option value="EPUB" <?= $livro['formato'] === 'EPUB' ? 'selected' : '' ?>>EPUB</option>
          <option value="Link Externo" <?= $livro['formato'] === 'Link Externo' ? 'selected' : '' ?>>Link Externo</option>
        </select>
      </div>
      <div class="col-md-4">
        <label for="link_digital" class="form-label">Link para leitura</label>
        <input type="url" name="link_digital" id="link_digital" class="form-control" value="<?= htmlspecialchars($livro['link_digital']) ?>">
      </div>
    </div>

    <div class="mb-3">
      <label for="capa" class="form-label">Atualizar Capa (opcional)</label>
      <input type="file" name="capa" id="capa" accept="image/*" class="form-control">
      <?php if (!empty($livro['capa_local'])): ?>
        <div class="mt-2">
          <img src="<?= URL_BASE . $livro['capa_local'] ?>" alt="Capa atual" class="img-thumbnail" style="max-height: 150px;">
        </div>
      <?php endif; ?>
    </div>

    <button type="submit" class="btn btn-primary">Atualizar Livro</button>
    <a href="listar_livros.php" class="btn btn-secondary">Cancelar</a>
  </form>
</div>

<?php require_once __DIR__ . '/../../../backend/includes/footer.php'; ?>
