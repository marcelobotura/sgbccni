<?php
session_start();
define('BASE_PATH', dirname(__DIR__, 2) . '/app_backend');
require_once BASE_PATH . '/config/config.php';
require_once BASE_PATH . '/includes/session.php';
include_once BASE_PATH . '/includes/header.php';

exigir_login('admin');
?>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<div class="container py-4">
  <h2>📘 Cadastro de Livro</h2>
  <form method="POST" action="salvar_livro.php">

    <div class="mb-3">
      <label>Título:</label>
      <input type="text" class="form-control" name="titulo" required>
    </div>

    <div class="row mb-3">
      <div class="col-md-4">
        <label>Autor:</label>
        <select class="form-select tag-selector" name="autor_nome" data-tipo="autor"></select>
      </div>
      <div class="col-md-4">
        <label>Editora:</label>
        <select class="form-select tag-selector" name="editora_nome" data-tipo="editora"></select>
      </div>
      <div class="col-md-4">
        <label>Categoria:</label>
        <select class="form-select tag-selector" name="categoria_nome" data-tipo="categoria"></select>
      </div>
    </div>

    <div class="row mb-3">
      <div class="col-md-3"><label>Ano:</label><input type="text" name="ano" class="form-control"></div>
      <div class="col-md-3"><label>Páginas:</label><input type="text" name="paginas" class="form-control"></div>
      <div class="col-md-3"><label>Idioma:</label><input type="text" name="idioma" class="form-control"></div>
      <div class="col-md-3"><label>ISBN:</label><input type="text" name="isbn" class="form-control"></div>
    </div>

    <div class="mb-3">
      <label>Descrição:</label>
      <textarea class="form-control" name="descricao" rows="3"></textarea>
    </div>

    <div class="row mb-3">
      <div class="col-md-4">
        <label>Formato:</label>
        <select name="formato" class="form-select" id="formato" onchange="mostrarLinkLeitura()">
          <option value="físico">Físico</option>
          <option value="digital">Digital</option>
        </select>
      </div>
      <div class="col-md-8" id="campo_link" style="display:none;">
        <label>Link para leitura ou download:</label>
        <input type="url" class="form-control" name="link_leitura" placeholder="https://...">
      </div>
    </div>

    <div class="mb-3">
      <label>Imagem da Capa (URL):</label>
      <input type="text" class="form-control" name="capa_url">
    </div>

    <button type="submit" class="btn btn-success">Salvar</button>
  </form>
</div>

<script>
function mostrarLinkLeitura() {
  const formato = document.getElementById('formato').value;
  document.getElementById('campo_link').style.display = (formato === 'digital') ? 'block' : 'none';
}

document.querySelectorAll('.tag-selector').forEach(function(select) {
  const tipo = select.dataset.tipo;
  $(select).select2({
    tags: true,
    placeholder: 'Digite ou selecione...',
    ajax: {
      url: 'buscar_tags.php',
      dataType: 'json',
      delay: 250,
      data: params => ({ q: params.term, tipo: tipo }),
      processResults: data => data,
    }
  });
});
</script>

<?php include_once BASE_PATH . '/includes/footer.php'; ?>
