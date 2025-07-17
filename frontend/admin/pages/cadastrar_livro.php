<?php
require_once __DIR__ . '/../../../backend/includes/protect_admin.php';
require_once __DIR__ . '/../../../backend/includes/header.php';
require_once __DIR__ . '/../../../backend/includes/menu.php';

exigir_login('admin');
?>

<div class="container py-4">
  <h2 class="mb-4">📚 Cadastrar Novo Livro</h2>

  <?php if (!empty($_SESSION['erro'])): ?>
    <div class="alert alert-danger"><?= htmlspecialchars($_SESSION['erro']); unset($_SESSION['erro']); ?></div>
  <?php elseif (!empty($_SESSION['sucesso'])): ?>
    <div class="alert alert-success"><?= htmlspecialchars($_SESSION['sucesso']); unset($_SESSION['sucesso']); ?></div>
  <?php endif; ?>

  <form action="<?= URL_BASE ?>backend/controllers/livros/salvar_livro.php" method="POST" enctype="multipart/form-data">
    <!-- 🔢 Identificação -->
    <div class="row mb-3">
      <div class="col-md-3">
        <label class="form-label">ISBN (13) *</label>
        <input type="text" name="isbn" id="isbn" class="form-control" required>
      </div>
      <div class="col-md-3">
        <label class="form-label">ISBN-10</label>
        <input type="text" name="isbn10" id="isbn10" class="form-control" readonly>
      </div>
      <div class="col-md-3">
        <label class="form-label">Código de Barras</label>
        <input type="text" name="codigo_barras" id="codigo_barras" class="form-control" readonly>
      </div>
      <div class="col-md-3">
        <label class="form-label">Código Interno *</label>
        <div class="input-group">
          <input type="text" name="codigo_interno" id="codigo_interno" class="form-control" required>
          <button type="button" class="btn btn-outline-secondary" onclick="gerarCodigoInterno()">Gerar</button>
        </div>
      </div>
    </div>

    <!-- 📖 Dados -->
    <div class="row mb-3">
      <div class="col-md-8">
        <label class="form-label">Título *</label>
        <input type="text" name="titulo" id="titulo" class="form-control" required>
      </div>
      <div class="col-md-4">
        <label class="form-label">Subtítulo</label>
        <input type="text" name="subtitulo" id="subtitulo" class="form-control">
      </div>
    </div>

    <div class="row mb-3">
      <div class="col-md-4">
        <label class="form-label">Volume</label>
        <input type="text" name="volume" id="volume" class="form-control">
      </div>
      <div class="col-md-4">
        <label class="form-label">Edição</label>
        <input type="text" name="edicao" id="edicao" class="form-control">
      </div>
      <div class="col-md-4">
        <label class="form-label">Ano</label>
        <input type="text" name="ano" id="ano" class="form-control">
      </div>
    </div>

    <div class="mb-3">
      <label class="form-label">Descrição</label>
      <textarea name="descricao" id="descricao" rows="4" class="form-control"></textarea>
    </div>

    <!-- 📄 Técnicos -->
    <div class="row mb-3">
      <div class="col-md-4">
        <label class="form-label">Tipo *</label>
        <select name="tipo" id="tipo" class="form-select" required>
          <option value="físico">Físico</option>
          <option value="digital">Digital</option>
        </select>
      </div>
      <div class="col-md-4">
        <label class="form-label">Formato</label>
        <select name="formato" id="formato" class="form-select">
          <option value="">Selecione</option>
          <option value="PDF">PDF</option>
          <option value="EPUB">EPUB</option>
          <option value="Link Externo">Link Externo</option>
        </select>
      </div>
      <div class="col-md-4">
        <label class="form-label">Link Digital</label>
        <input type="url" name="link_digital" id="link_digital" class="form-control">
      </div>
    </div>

    <!-- 🏷️ Tags -->
    <div class="row mb-3">
      <div class="col-md-4">
        <label class="form-label">Autor *</label>
        <select name="autor_id" id="autor_id" class="form-select" required></select>
      </div>
      <div class="col-md-4">
        <label class="form-label">Editora *</label>
        <select name="editora_id" id="editora_id" class="form-select" required></select>
      </div>
      <div class="col-md-4">
        <label class="form-label">Categoria *</label>
        <select name="categoria_id" id="categoria_id" class="form-select" required></select>
      </div>
    </div>

    <!-- 🖼️ Capa -->
    <div class="row mb-3">
      <div class="col-md-6">
        <label class="form-label">Capa do Livro</label>
        <input type="file" name="capa" id="capa" accept="image/*" class="form-control">
        <img id="preview_capa" class="img-thumbnail mt-3 d-none" style="max-height: 200px;">
      </div>
      <div class="col-md-6">
        <label class="form-label">Fonte dos Dados</label>
        <input type="text" name="fonte" id="fonte" class="form-control" readonly placeholder="Manual ou via API">
      </div>
    </div>

    <button type="submit" class="btn btn-success">💾 Salvar Livro</button>
  </form>
</div>

<!-- 📦 Scripts -->
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
  gerarCodigoInterno();
});

function gerarCodigoInterno() {
  const isbn = document.getElementById('isbn').value.trim();
  const titulo = document.getElementById('titulo').value.trim();
  let codigo = '';

  if (isbn) {
    codigo = 'LIV-' + isbn.slice(-6);
  } else if (titulo) {
    const slug = titulo.substring(0, 4).toUpperCase().replace(/[^A-Z0-9]/g, '');
    const random = Math.floor(1000 + Math.random() * 9000);
    codigo = `LIV-${slug}${random}`;
  } else {
    const random = Math.floor(100000 + Math.random() * 900000);
    codigo = 'LIV-' + random;
  }

  document.getElementById('codigo_interno').value = codigo;
}

document.getElementById('isbn').addEventListener('blur', function () {
  const isbn = this.value.trim();
  if (!isbn) return;

  fetch('<?= URL_BASE ?>backend/services/isbn_lookup.php?isbn=' + isbn)
    .then(res => res.json())
    .then(data => {
      if (data.erro) {
        alert(data.erro);
        document.getElementById('fonte').value = 'Erro na busca';
        return;
      }

      document.getElementById('titulo').value = data.titulo || '';
      document.getElementById('subtitulo').value = data.subtitulo || '';
      document.getElementById('descricao').value = data.descricao || '';
      document.getElementById('ano').value = data.ano || '';
      document.getElementById('isbn10').value = data.isbn10 || '';
      document.getElementById('codigo_barras').value = data.codigo_barras || '';
      document.getElementById('fonte').value = data.fonte || 'Desconhecida';

      if (data.autor) {
        const opt = new Option(data.autor, data.autor, true, true);
        $('#autor_id').append(opt).trigger('change');
      }
      if (data.editora) {
        const opt = new Option(data.editora, data.editora, true, true);
        $('#editora_id').append(opt).trigger('change');
      }
      if (data.categoria) {
        const opt = new Option(data.categoria, data.categoria, true, true);
        $('#categoria_id').append(opt).trigger('change');
      }

      if (data.capa) {
        const preview = document.getElementById('preview_capa');
        preview.src = data.capa;
        preview.classList.remove('d-none');
      }
    })
    .catch(() => {
      alert('Erro ao buscar ISBN.');
      document.getElementById('fonte').value = 'Erro na busca';
    });
});
</script>

<?php require_once __DIR__ . '/../../../backend/includes/footer.php'; ?>
