<?php
require_once __DIR__ . '/../../../backend/config/config.php';
require_once __DIR__ . '/../../../backend/includes/protect_admin.php';
require_once __DIR__ . '/../../../backend/includes/header.php';
require_once __DIR__ . '/../../../backend/includes/menu.php';

exigir_login('admin');

// Valida o ID
$id = intval($_GET['id'] ?? 0);
if ($id <= 0) {
  $_SESSION['erro'] = 'ID inválido.';
  header('Location: listar_livros.php');
  exit;
}

// Busca o livro
$stmt = $conn->prepare("SELECT * FROM livros WHERE id = :id");
$stmt->bindParam(':id', $id);
$stmt->execute();
$livro = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$livro) {
  $_SESSION['erro'] = 'Livro não encontrado.';
  header('Location: listar_livros.php');
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

  <form action="<?= URL_BASE ?>backend/controllers/livros/salvar_edicao_livro.php" method="POST">
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

    <div class="row mb-3">
      <div class="col-md-4">
        <label for="volume" class="form-label">Volume</label>
        <input type="text" name="volume" id="volume" class="form-control" value="<?= htmlspecialchars($livro['volume']) ?>">
      </div>
      <div class="col-md-4">
        <label for="edicao" class="form-label">Edição</label>
        <input type="text" name="edicao" id="edicao" class="form-control" value="<?= htmlspecialchars($livro['edicao']) ?>">
      </div>
      <div class="col-md-4">
        <label for="codigo_interno" class="form-label">Código Interno *</label>
        <input type="text" name="codigo_interno" id="codigo_interno" class="form-control" value="<?= htmlspecialchars($livro['codigo_interno']) ?>" required>
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
          <option value="físico" <?= $livro['tipo'] == 'físico' ? 'selected' : '' ?>>Físico</option>
          <option value="digital" <?= $livro['tipo'] == 'digital' ? 'selected' : '' ?>>Digital</option>
        </select>
      </div>
      <div class="col-md-4">
        <label for="formato" class="form-label">Formato</label>
        <select name="formato" id="formato" class="form-select">
          <option value="PDF" <?= $livro['formato'] == 'PDF' ? 'selected' : '' ?>>PDF</option>
          <option value="EPUB" <?= $livro['formato'] == 'EPUB' ? 'selected' : '' ?>>EPUB</option>
          <option value="Link Externo" <?= $livro['formato'] == 'Link Externo' ? 'selected' : '' ?>>Link Externo</option>
        </select>
      </div>
      <div class="col-md-4">
        <label for="link_digital" class="form-label">Link de Leitura</label>
        <input type="url" name="link_digital" id="link_digital" class="form-control" value="<?= htmlspecialchars($livro['link_digital']) ?>">
      </div>
    </div>

    <div class="row mb-3">
      <div class="col-md-4">
        <label for="autor_id" class="form-label">Autor</label>
        <input type="text" name="autor_id" id="autor_id" class="form-control" value="<?= htmlspecialchars($livro['autor_id']) ?>">
      </div>
      <div class="col-md-4">
        <label for="editora_id" class="form-label">Editora</label>
        <input type="text" name="editora_id" id="editora_id" class="form-control" value="<?= htmlspecialchars($livro['editora_id']) ?>">
      </div>
      <div class="col-md-4">
        <label for="categoria_id" class="form-label">Categoria</label>
        <input type="text" name="categoria_id" id="categoria_id" class="form-control" value="<?= htmlspecialchars($livro['categoria_id']) ?>">
      </div>
    </div>

    <button type="submit" class="btn btn-success">Salvar Alterações</button>
    <a href="listar_livros.php" class="btn btn-secondary ms-2">Cancelar</a>
  </form>
</div>

<?php require_once __DIR__ . '/../../../backend/includes/footer.php'; ?>
