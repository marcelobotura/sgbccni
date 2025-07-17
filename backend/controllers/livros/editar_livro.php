<?php
require_once __DIR__ . '/../../../backend/includes/session.php';
require_once __DIR__ . '/../../../backend/includes/protect_admin.php';
require_once __DIR__ . '/../../../backend/includes/header.php';
require_once __DIR__ . '/../../../backend/includes/menu.php';

exigir_login('admin');
require_once __DIR__ . '/../../../backend/config/config.php';

// Função auxiliar para exibir nome da tag
function buscarNomeTag($pdo, $id) {
    if (!$id) return '';
    $stmt = $pdo->prepare("SELECT nome FROM tags WHERE id = ?");
    $stmt->execute([$id]);
    $tag = $stmt->fetch(PDO::FETCH_ASSOC);
    return $tag['nome'] ?? '';
}

// Validação do ID
$id = $_GET['id'] ?? null;
if (!$id || !is_numeric($id)) {
    $_SESSION['erro'] = 'Livro não especificado.';
    header("Location: listar_livros.php");
    exit;
}

// Busca o livro
$stmt = $pdo->prepare("SELECT * FROM livros WHERE id = ?");
$stmt->execute([$id]);
$livro = $stmt->fetch(PDO::FETCH_ASSOC);

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

  <form action="<?= URL_BASE ?>backend/controllers/livros/salvar_edicao_livro.php" method="POST" enctype="multipart/form-data">
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
          <option value="físico" <?= $livro['tipo'] === 'físico' ? 'selected' : '' ?>>Físico</option>
          <option value="digital" <?= $livro['tipo'] === 'digital' ? 'selected' : '' ?>>Digital</option>
        </select>
      </div>
      <div class="col-md-4">
        <label for="formato" class="form-label">Formato</label>
        <select name="formato" id="formato" class="form-select">
          <option value="">Selecione</option>
          <option value="PDF" <?= $livro['formato'] === 'PDF' ? 'selected' : '' ?>>PDF</option>
          <option value="EPUB" <?= $livro['formato'] === 'EPUB' ? 'selected' : '' ?>>EPUB</option>
          <option value="Link Externo" <?= $livro['formato'] === 'Link Externo' ? 'selected' : '' ?>>Link Externo</option>
        </select>
      </div>
      <div class="col-md-4">
        <label for="link_digital" class="form-label">Link para Leitura</label>
        <input type="url" name="link_digital" id="link_digital" class="form-control" value="<?= htmlspecialchars($livro['link_digital']) ?>">
      </div>
    </div>

    <div class="mb-3">
      <label for="nova_capa" class="form-label">Atualizar Capa (opcional)</label>
      <input type="file" name="nova_capa" id="nova_capa" class="form-control" accept="image/*">
      <?php if (!empty($livro['capa'])): ?>
        <img src="<?= URL_BASE . 'uploads/capas/' . $livro['capa'] ?>" class="img-thumbnail mt-2" style="max-height: 150px;">
      <?php else: ?>
        <p class="text-muted mt-2">Sem capa cadastrada.</p>
      <?php endif; ?>
    </div>

    <!-- Select2 para Tags -->
    <div class="row mb-3">
      <div class="col-md-4">
        <label for="autor_id" class="form-label">Autor *</label>
        <select name="autor_id" id="autor_id" class="form-select" required>
          <option value="<?= $livro['autor_id'] ?>" selected><?= buscarNomeTag($pdo, $livro['autor_id']) ?></option>
        </select>
      </div>
      <div class="col-md-4">
        <label for="editora_id" class="form-label">Editora *</label>
        <select name="editora_id" id="editora_id" class="form-select" required>
          <option value="<?= $livro['editora_id'] ?>" selected><?= buscarNomeTag($pdo, $livro['editora_id']) ?></option>
        </select>
      </div>
      <div class="col-md-4">
        <label for="categoria_id" class="form-label">Categoria *</label>
        <select name="categoria_id" id="categoria_id" class="form-select" required>
          <option value="<?= $livro['categoria_id'] ?>" selected><?= buscarNomeTag($pdo, $livro['categoria_id']) ?></option>
        </select>
      </div>
    </div>

    <div class="d-flex justify-content-between">
      <button type="submit" class="btn btn-primary">💾 Atualizar Livro</button>
      <a href="listar_livros.php" class="btn btn-secondary">Cancelar</a>
    </div>
  </form>
</div>

<!-- Select2 + Scripts -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet"/>
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
function configurarTag(id, tipo) {
  $('#' + id).select2({
    theme: 'bootstrap-5',
    tags: true,
    ajax: {
      url: '<?= URL_BASE ?>backend/services/buscar_tags.php',
      dataType: 'json',
      delay: 250,
      data: params => ({ tipo: tipo, q: params.term }),
      processResults: data => data
    },
    placeholder: 'Digite para buscar ou adicionar...'
  });
}

document.addEventListener('DOMContentLoaded', () => {
  configurarTag('autor_id', 'autor');
  configurarTag('editora_id', 'editora');
  configurarTag('categoria_id', 'categoria');
});
</script>

<?php require_once __DIR__ . '/../../../backend/includes/footer.php'; ?>
