<?php
// Caminho: frontend/admin/pages/editar_midia.php

define('BASE_PATH', dirname(__DIR__, 3));
require_once BASE_PATH . '/backend/config/config.php';
require_once BASE_PATH . '/backend/includes/session.php';
require_once BASE_PATH . '/backend/includes/protect_admin.php';
require_once BASE_PATH . '/backend/includes/header.php';
require_once BASE_PATH . '/backend/includes/menu.php';

exigir_login('admin');

$id = (int)($_GET['id'] ?? 0);
if ($id <= 0) {
  $_SESSION['erro'] = "ID inválido.";
  header("Location: listar_midias.php");
  exit;
}

$stmt = $pdo->prepare("SELECT * FROM midias WHERE id = ?");
$stmt->execute([$id]);
$midia = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$midia) {
  $_SESSION['erro'] = "Mídia não encontrada.";
  header("Location: listar_midias.php");
  exit;
}

function capaMidia($midia) {
  if (!empty($midia['capa_local']) && file_exists(BASE_PATH . '/../storage/uploads/capas/' . $midia['capa_local'])) {
    return URL_BASE . 'storage/uploads/capas/' . $midia['capa_local'];
  } elseif (!empty($midia['capa_url'])) {
    return $midia['capa_url'];
  }
  return URL_BASE . 'frontend/assets/img/livro_padrao.png';
}
?>

<div class="container py-4">
  <h2><i class="bi bi-pencil-square"></i> Editar Mídia</h2>

  <?php if (!empty($_SESSION['erro'])): ?>
    <div class="alert alert-danger"><?= htmlspecialchars($_SESSION['erro']); unset($_SESSION['erro']); ?></div>
  <?php elseif (!empty($_SESSION['sucesso'])): ?>
    <div class="alert alert-success"><?= htmlspecialchars($_SESSION['sucesso']); unset($_SESSION['sucesso']); ?></div>
  <?php endif; ?>

  <form action="<?= URL_BASE ?>backend/controllers/midias/salvar_edicao_midia.php" method="POST" enctype="multipart/form-data">
    <input type="hidden" name="id" value="<?= $midia['id'] ?>">

    <!-- Título -->
    <div class="mb-3">
      <label class="form-label">Título *</label>
      <input type="text" name="titulo" class="form-control" required value="<?= htmlspecialchars($midia['titulo']) ?>">
    </div>

    <!-- Autor -->
    <div class="mb-3">
      <label class="form-label">Autor / Canal</label>
      <input type="text" name="autor" class="form-control" value="<?= htmlspecialchars($midia['autor']) ?>">
    </div>

    <!-- Descrição -->
    <div class="mb-3">
      <label class="form-label">Descrição</label>
      <textarea name="descricao" class="form-control" rows="3"><?= htmlspecialchars($midia['descricao']) ?></textarea>
    </div>

    <!-- Plataforma -->
    <div class="mb-3">
      <label class="form-label">Plataforma</label>
      <input type="text" name="plataforma" class="form-control" value="<?= htmlspecialchars($midia['plataforma']) ?>">
    </div>

    <!-- Data Publicação e Duração -->
    <div class="row">
      <div class="col-md-6 mb-3">
        <label class="form-label">Data de Publicação</label>
        <input type="date" name="data_publicacao" class="form-control" value="<?= htmlspecialchars($midia['data_publicacao']) ?>">
      </div>
      <div class="col-md-6 mb-3">
        <label class="form-label">Duração</label>
        <input type="text" name="duracao" class="form-control" value="<?= htmlspecialchars($midia['duracao']) ?>">
      </div>
    </div>

    <!-- URL da Mídia -->
    <div class="mb-3">
      <label class="form-label">URL da Mídia *</label>
      <input type="url" name="url" class="form-control" required value="<?= htmlspecialchars($midia['url']) ?>">
    </div>

    <!-- Capa atual -->
    <div class="mb-3">
      <label class="form-label">Capa Atual</label><br>
      <img src="<?= capaMidia($midia) ?>" alt="Capa atual" style="height: 180px; border-radius: 8px;">
    </div>

    <!-- Nova Capa Local -->
    <div class="mb-3">
      <label class="form-label">Nova Capa (Upload)</label>
      <input type="file" name="capa_local" class="form-control">
    </div>

    <!-- URL da Capa -->
    <div class="mb-3">
      <label class="form-label">URL da Capa (opcional)</label>
      <input type="url" name="capa_url" class="form-control" value="<?= htmlspecialchars($midia['capa_url']) ?>">
    </div>

    <!-- Tipo -->
    <div class="mb-3">
      <label class="form-label">Tipo</label>
      <select name="tipo" class="form-select" required>
        <option value="">Selecione</option>
        <option value="podcast" <?= $midia['tipo'] === 'podcast' ? 'selected' : '' ?>>Podcast</option>
        <option value="video" <?= $midia['tipo'] === 'video' ? 'selected' : '' ?>>Vídeo</option>
        <option value="audio" <?= $midia['tipo'] === 'audio' ? 'selected' : '' ?>>Áudio</option>
        <option value="outro" <?= $midia['tipo'] === 'outro' ? 'selected' : '' ?>>Outro</option>
      </select>
    </div>

    <button type="submit" class="btn btn-success"><i class="bi bi-save"></i> Salvar Alterações</button>
    <a href="listar_midias.php" class="btn btn-secondary">← Voltar</a>
  </form>
</div>
