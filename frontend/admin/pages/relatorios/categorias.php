<?php
// Caminho: frontend/admin/pages/relatorios/categorias.php

define('BASE_PATH', dirname(__DIR__, 4));
require_once BASE_PATH . '/backend/config/config.php';
require_once BASE_PATH . '/backend/includes/session.php';
require_once BASE_PATH . '/backend/includes/protect_admin.php';
require_once BASE_PATH . '/backend/includes/header.php';
require_once BASE_PATH . '/backend/includes/menu.php';

exigir_login('admin');

// Consulta: Categorias mais lidas
$sql = "
SELECT t.nome AS categoria, COUNT(lu.id) AS total
FROM livros_usuarios lu
JOIN livros l ON l.id = lu.livro_id
JOIN tags t ON t.id = l.categoria_id
WHERE lu.status = 'lido'
GROUP BY t.id
ORDER BY total DESC
";
$stmt = $pdo->query($sql);
$categorias = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="container py-4">
  <h2 class="mb-4"><i class="bi bi-tags-fill"></i> Relatório de Categorias Mais Lidas</h2>

  <?php if (count($categorias) === 0): ?>
    <div class="alert alert-warning">Nenhuma leitura registrada por categoria até o momento.</div>
  <?php else: ?>
    <div class="table-responsive">
      <table class="table table-bordered table-striped align-middle">
        <thead class="table-dark">
          <tr>
            <th>#</th>
            <th>Categoria</th>
            <th>Total de Leituras</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($categorias as $i => $row): ?>
            <tr>
              <td><?= $i + 1 ?></td>
              <td><?= htmlspecialchars($row['categoria']) ?></td>
              <td><span class="badge bg-success"><?= $row['total'] ?></span></td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  <?php endif; ?>
</div>

<?php require_once BASE_PATH . '/backend/includes/footer.php'; ?>
