<?php
require_once __DIR__ . '/../../../backend/includes/verifica_admin.php';
require_once __DIR__ . '/../../../backend/includes/header.php';
require_once __DIR__ . '/../../../backend/includes/menu.php';
require_once __DIR__ . 'protect_admin.php';
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

    <div class="row mb-3">
      <div class="col-md-8">
        <label for="titulo" class="form-label">Título *</label>
        <input type="text" name="titulo" id="titulo" class="form-control" required>
      </div>
      <div class="col-md-4">
        <label for="isbn" class="form-label">ISBN *</label>
        <input type="text" name="isbn" id="isbn" class="form-control" required>
      </div>
    </div>

    <div class="row mb-3">
      <div class="col-md-4">
        <label for="volume" class="form-label">Volume</label>
        <input type="text" name="volume" id="volume" class="form-control">
      </div>
      <div class="col-md-4">
        <label for="edicao" class="form-label">Edição</label>
        <input type="text" name="edicao" id="edicao" class="form-control">
      </div>
      <div class="col-md-4">
        <label for="codigo_interno" class="form-label">Código Interno *</label>
        <input type="text" name="codigo_interno" id="codigo_interno" class="form-control" required>
      </div>
    </div>

    <div class="mb-3">
      <label for="descricao" class="form-label">Descrição</label>
      <textarea name="descricao" id="descricao" rows="4" class="form-control"></textarea>
    </div>

    <div class="row mb-3">
      <div class="col-md-4">
        <label for="tipo" class="form-label">Tipo</label>
        <select name="tipo" id="tipo" class="form-select">
          <option value="físico">Físico</option>
          <option value="digital">Digital</option>
        </select>
      </div>
      <div class="col-md-4">
        <label for="formato" class="form-label">Formato</label>
        <select name="formato" id="formato" class="form-select">
          <option value="PDF">PDF</option>
          <option value="EPUB">EPUB</option>
          <option value="Link Externo">Link Externo</option>
        </select>
      </div>
      <div class="col-md-4">
        <label for="link_digital" class="form-label">Link de Leitura (se digital)</label>
        <input type="url" name="link_digital" id="link_digital" class="form-control">
      </div>
    </div>

    <div class="row mb-3">
      <div class="col-md-4">
        <label for="autor_id" class="form-label">Autor</label>
        <select name="autor_id" id="autor_id" class="form-select"></select>
      </div>
      <div class="col-md-4">
        <label for="editora_id" class="form-label">Editora</label>
        <select name="editora_id" id="editora_id" class="form-select"></select>
      </div>
      <div class="col-md-4">
        <label for="categoria_id" class="form-label">Categoria</label>
        <select name="categoria_id" id="categoria_id" class="form-select"></select>
      </div>
    </div>

    <div class="mb-3">
      <label for="capa" class="form-label">Capa do Livro</label>
      <input type="file" name="capa" id="capa" accept="image/*" class="form-control">
    </div>

    <button type="submit" class="btn btn-success">Salvar Livro</button>
  </form>
</div>

<!-- Select2 -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
// 🔁 Configura campos de tags com autocomplete
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
    createTag: function (params) {
      const nome = $.trim(params.term);
      if (nome === '') return null;
      return { id: nome, text: nome, newOption: true };
    },
    insertTag: function (data, tag) {
      $.post('<?= URL_BASE ?>frontend/admin/ajax/salvar_tag.php', {
        nome: tag.text,
        tipo: tipo
      }, function (resp) {
        if (resp.status === 'ok' || resp.status === 'existe') {
          const newOption = new Option(resp.text, resp.id, true, true);
          $('#' + id).append(newOption).trigger('change');
        } else {
          alert(resp.mensagem);
        }
      }, 'json');
    },
    placeholder: 'Digite para buscar ou adicionar...'
  });
}

document.addEventListener('DOMContentLoaded', function () {
  configurarTag('autor_id', 'autor');
  configurarTag('editora_id', 'editora');
  configurarTag('categoria_id', 'categoria');
});

// 🔍 Busca ISBN automaticamente e preenche os campos
document.getElementById('isbn').addEventListener('blur', function () {
  const isbn = this.value.trim();
  if (!isbn) return;

  fetch('<?= URL_BASE ?>backend/services/buscar_isbn.php?isbn=' + isbn)
    .then(res => res.json())
    .then(data => {
      if (data.erro) {
        alert(data.erro);
        return;
      }

      document.getElementById('titulo').value = data.titulo || '';
      document.getElementById('descricao').value = data.descricao || '';

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
        let preview = document.getElementById('preview_capa');
        if (!preview) {
          preview = document.createElement('img');
          preview.id = 'preview_capa';
          preview.classList.add('img-thumbnail', 'mt-3');
          preview.style.maxHeight = '200px';
          document.getElementById('capa').insertAdjacentElement('afterend', preview);
        }
        preview.src = data.capa;
      }
    })
    .catch(() => alert('Erro ao buscar informações do ISBN'));
});
</script>

<?php require_once __DIR__ . '/../../../backend/includes/footer.php'; ?>
