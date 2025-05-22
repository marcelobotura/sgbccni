<?php
session_start();
define('BASE_PATH', dirname(__DIR__, 2) . '/app_backend');
require_once BASE_PATH . '/config/config.php';
require_once BASE_PATH . '/includes/session.php';
include_once BASE_PATH . '/includes/header.php'; // Inclua seu cabeçalho HTML aqui

exigir_login('admin');
?>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<div class="container py-4">
  <h2>📘 Cadastro de Livro</h2>
  <?php exibir_mensagens_sessao(); // Chamada para exibir as mensagens de sucesso/erro ?>
  <form method="POST" action="salvar_livro.php">

    <div class="mb-3">
      <label for="titulo">Título:</label>
      <input type="text" class="form-control" id="titulo" name="titulo" required>
    </div>

    <div class="row mb-3">
      <div class="col-md-4">
        <label for="autor_nome">Autor:</label>
        <select class="form-select tag-selector" id="autor_nome" name="autor_nome" data-tipo="autor" required></select>
      </div>
      <div class="col-md-4">
        <label for="editora_nome">Editora:</label>
        <select class="form-select tag-selector" id="editora_nome" name="editora_nome" data-tipo="editora" required></select>
      </div>
      <div class="col-md-4">
        <label for="categoria_nome">Categoria:</label>
        <select class="form-select tag-selector" id="categoria_nome" name="categoria_nome" data-tipo="categoria" required></select>
      </div>
    </div>

    <div class="row mb-3">
      <div class="col-md-3"><label for="ano">Ano:</label><input type="number" id="ano" name="ano" class="form-control" placeholder="Ex: 2023" min="1000" max="<?= date('Y') ?>"></div>
      <div class="col-md-3"><label for="paginas">Páginas:</label><input type="number" id="paginas" name="paginas" class="form-control" placeholder="Ex: 300" min="1"></div>
      <div class="col-md-3"><label for="idioma">Idioma:</label><input type="text" id="idioma" name="idioma" class="form-control" placeholder="Ex: Português"></div>
      <div class="col-md-3"><label for="isbn">ISBN:</label><input type="text" id="isbn" name="isbn" class="form-control" placeholder="Ex: 978-0321765723"></div>
    </div>

    <div class="mb-3">
      <label for="descricao">Descrição:</label>
      <textarea class="form-control" id="descricao" name="descricao" rows="3"></textarea>
    </div>

    <div class="row mb-3">
      <div class="col-md-4">
        <label for="formato">Formato:</label>
        <select name="formato" class="form-select" id="formato" onchange="mostrarLinkLeitura()">
          <option value="físico">Físico</option>
          <option value="digital">Digital</option>
        </select>
      </div>
      <div class="col-md-8" id="campo_link" style="display:none;">
        <label for="link_leitura">Link para leitura ou download:</label>
        <input type="url" class="form-control" id="link_leitura" name="link_leitura" placeholder="https://...">
      </div>
    </div>

    <div class="mb-3">
      <label for="capa_url">Imagem da Capa (URL):</label>
      <input type="url" class="form-control" id="capa_url" name="capa_url" placeholder="https://...">
    </div>

    <button type="submit" class="btn btn-success">Salvar</button>
  </form>
</div>

<script>
function mostrarLinkLeitura() {
  const formato = document.getElementById('formato').value;
  document.getElementById('campo_link').style.display = (formato === 'digital') ? 'block' : 'none';
  // Adiciona/remove 'required' dinamicamente
  document.getElementById('link_leitura').required = (formato === 'digital');
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

// Chamar ao carregar a página para garantir o estado correto do campo de link
document.addEventListener('DOMContentLoaded', mostrarLinkLeitura);
</script>

<?php include_once BASE_PATH . '/includes/footer.php'; // Inclua seu rodapé HTML aqui ?>