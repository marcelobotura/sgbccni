<?php
// Caminho: frontend/admin/pages/listar_midias.php

define('BASE_PATH', dirname(__DIR__, 3));
require_once BASE_PATH . '/backend/config/config.php';
require_once BASE_PATH . '/backend/includes/session.php';
require_once BASE_PATH . '/backend/includes/protect_admin.php';
require_once BASE_PATH . '/backend/includes/header.php';
require_once BASE_PATH . '/backend/includes/menu.php';

exigir_login('admin');

// Filtro
$tipo = $_GET['tipo'] ?? '';
$filtro_sql = '';
$params = [];

if (!empty($tipo)) {
  $filtro_sql = "WHERE tipo = :tipo";
  $params[':tipo'] = $tipo;
}

// Consulta
$stmt = $pdo->prepare("SELECT * FROM midias $filtro_sql ORDER BY criado_em DESC");
$stmt->execute($params);
$midias = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Tipos disponíveis
$tipos_disponiveis = ['podcast', 'video', 'audio', 'outro'];

// Função para capa
function getCapa($midia) {
  if (!empty($midia['capa_local']) && file_exists(BASE_PATH . '/../storage/uploads/capas/' . $midia['capa_local'])) {
    return URL_BASE . 'storage/uploads/capas/' . $midia['capa_local'];
  }
  return $midia['capa_url'] ?: URL_BASE . 'frontend/assets/img/livro_padrao.png';
}
?>

<div class="container py-4">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h3><i class="bi bi-collection-play"></i> Mídias Cadastradas</h3>
    <a href="cadastrar_midia.php" class="btn btn-success"><i class="bi bi-plus-circle"></i> Nova Mídia</a>
  </div>

  <form method="get" class="mb-3">
    <div class="input-group">
      <select name="tipo" class="form-select" onchange="this.form.submit()">
        <option value="">📁 Todos os Tipos</option>
        <?php foreach ($tipos_disponiveis as $t): ?>
          <option value="<?= $t ?>" <?= $tipo === $t ? 'selected' : '' ?>>
            <?= ucfirst($t) ?>
          </option>
        <?php endforeach; ?>
      </select>
      <button type="submit" class="btn btn-primary">Filtrar</button>
    </div>
  </form>

  <?php if (count($midias) === 0): ?>
    <div class="alert alert-warning">Nenhuma mídia encontrada.</div>
  <?php else: ?>
    <div class="row row-cols-1 row-cols-md-3 g-4">
      <?php foreach ($midias as $midia): ?>
        <div class="col">
          <div class="card h-100 shadow-sm">
            <img src="<?= getCapa($midia) ?>" class="card-img-top" alt="Capa" style="height: 180px; object-fit: cover;">
            <div class="card-body">
              <h5 class="card-title"><?= htmlspecialchars($midia['titulo']) ?></h5>
              <p class="card-text small"><?= htmlspecialchars(mb_strimwidth($midia['descricao'], 0, 120, '...')) ?></p>
              <span class="badge bg-secondary"><?= ucfirst($midia['tipo']) ?></span>
              <span class="badge bg-info text-dark"><?= htmlspecialchars($midia['plataforma']) ?></span>
            </div>
            <div class="card-footer d-flex justify-content-between">
              <a href="editar_midia.php?id=<?= $midia['id'] ?>" class="btn btn-sm btn-outline-primary"><i class="bi bi-pencil"></i> Editar</a>
              <a href="excluir_midia.php?id=<?= $midia['id'] ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Deseja excluir esta mídia?')"><i class="bi bi-trash"></i> Excluir</a>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>
</div>

<?php require_once BASE_PATH . '/backend/includes/footer.php'; ?>
