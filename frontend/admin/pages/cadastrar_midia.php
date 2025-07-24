<?php
// Caminho: frontend/admin/pages/cadastrar_midia.php

define('BASE_PATH', dirname(__DIR__, 3));
require_once BASE_PATH . '/backend/config/config.php';
require_once BASE_PATH . '/backend/includes/session.php';
require_once BASE_PATH . '/backend/includes/protect_admin.php';
require_once BASE_PATH . '/backend/includes/header.php';
require_once BASE_PATH . '/backend/includes/menu.php';

exigir_login('admin');
?>
<div class="container py-4">
  <h2 class="mb-4"><i class="bi bi-upload"></i> Cadastrar Mídia (Podcast / Vídeo)</h2>

  <?php if (!empty($_SESSION['erro'])): ?>
    <div class="alert alert-danger"><?= htmlspecialchars($_SESSION['erro']); unset($_SESSION['erro']); ?></div>
  <?php elseif (!empty($_SESSION['sucesso'])): ?>
    <div class="alert alert-success"><?= htmlspecialchars($_SESSION['sucesso']); unset($_SESSION['sucesso']); ?></div>
  <?php endif; ?>

  <form action="<?= URL_BASE ?>backend/controllers/midias/salvar_midia.php" method="POST" id="formMidia" enctype="multipart/form-data">
    <!-- URL da Mídia -->
    <div class="mb-3">
      <label for="url" class="form-label">URL do Vídeo / Podcast *</label>
      <div class="input-group">
        <input type="url" name="url" id="url" class="form-control" required placeholder="https://...">
        <button type="button" id="buscarDados" class="btn btn-outline-primary">
          <i class="bi bi-search"></i> Buscar dados
        </button>
      </div>
    </div>

    <!-- Título -->
    <div class="mb-3">
      <label for="titulo" class="form-label">Título *</label>
      <input type="text" name="titulo" id="titulo" class="form-control" required>
    </div>

    <!-- Subtítulo / Autor -->
    <div class="mb-3">
      <label for="autor" class="form-label">Autor / Canal</label>
      <input type="text" name="autor" id="autor" class="form-control">
    </div>

    <!-- Descrição -->
    <div class="mb-3">
      <label for="descricao" class="form-label">Descrição</label>
      <textarea name="descricao" id="descricao" class="form-control" rows="3"></textarea>
    </div>

    <!-- Plataforma -->
    <div class="mb-3">
      <label for="plataforma" class="form-label">Plataforma (YouTube, Spotify, etc.)</label>
      <input type="text" name="plataforma" id="plataforma" class="form-control">
    </div>

    <!-- Data / Duração -->
    <div class="row">
      <div class="col-md-6 mb-3">
        <label for="data_publicacao" class="form-label">Data de Publicação</label>
        <input type="date" name="data_publicacao" id="data_publicacao" class="form-control">
      </div>
      <div class="col-md-6 mb-3">
        <label for="duracao" class="form-label">Duração (ex: 4min 32s)</label>
        <input type="text" name="duracao" id="duracao" class="form-control">
      </div>
    </div>

    <!-- Upload ou URL da Capa -->
    <div class="mb-3">
      <label for="capa_upload" class="form-label">Capa da Mídia (arquivo ou URL)</label>
      <input type="file" name="capa_upload" id="capa_upload" class="form-control mb-2" accept="image/*">
      <input type="hidden" name="capa_url" id="capa_url">
    </div>

    <!-- Tipo -->
    <div class="mb-3">
      <label for="tipo" class="form-label">Tipo *</label>
      <select name="tipo" id="tipo" class="form-select" required>
        <option value="">Selecione</option>
        <option value="podcast">Podcast</option>
        <option value="video">Vídeo</option>
        <option value="audio">Áudio</option>
        <option value="outro">Outro</option>
      </select>
    </div>

    <button type="submit" class="btn btn-success"><i class="bi bi-save"></i> Salvar Mídia</button>
  </form>
</div>

<script>
document.getElementById('buscarDados').addEventListener('click', async () => {
  const url = document.getElementById('url').value.trim();
  if (!url) return alert('Informe uma URL válida primeiro.');

  const formData = new FormData();
  formData.append('url', url);

  const resposta = await fetch('<?= URL_BASE ?>backend/controllers/midias/buscar_info_midia.php', {
    method: 'POST',
    body: formData
  });

  const dados = await resposta.json();
  if (dados.sucesso) {
    document.getElementById('titulo').value = dados.titulo || '';
    document.getElementById('descricao').value = dados.descricao || '';
    document.getElementById('plataforma').value = dados.plataforma || '';
    document.getElementById('duracao').value = dados.duracao || '';
    document.getElementById('capa_url').value = dados.capa || '';
    document.getElementById('autor').value = dados.autor || '';
  } else {
    alert('❌ Não foi possível obter os dados da mídia.');
  }
});
</script>
