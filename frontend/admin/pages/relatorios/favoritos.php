<?php
// Caminho: frontend/admin/pages/relatorios/favoritos.php

define('BASE_PATH', dirname(__DIR__, 4));
require_once BASE_PATH . '/backend/config/config.php';
require_once BASE_PATH . '/backend/includes/session.php';
require_once BASE_PATH . '/backend/includes/protect_admin.php';
require_once BASE_PATH . '/backend/includes/header.php';
require_once BASE_PATH . '/backend/includes/menu.php';

exigir_login('admin');

// Consulta: Livros com mais favoritos
$sql = "
SELECT l.titulo, COUNT(lu.id) AS total
FROM livros_usuarios lu
JOIN livros l ON l.id = lu.livro_id
WHERE lu.status = 'favorito'
GROUP BY l.id
ORDER BY total DESC
";
$stmt = $pdo->query($sql);
$favoritos = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="container py-4">
  <h2 class="mb-4"><i class="bi bi-star-fill"></i> Relatório de Livros Mais Favoritados</h2>

  <?php if (count($favoritos) === 0): ?>
    <div class="alert alert-warning">Nenhum favorito registrado até o momento.</div>
  <?php else: ?>
    <div class="table-responsive">
      <table class="table table-bordered table-striped align-middle">
        <thead class="table-dark">
          <tr>
            <th>#</th>
            <th>Título do Livro</th>
            <th>Total de Favoritos</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($favoritos as $i => $row): ?>
            <tr>
              <td><?= $i + 1 ?></td>
              <td><?= htmlspecialchars($row['titulo']) ?></td>
              <td><span class="badge bg-warning text-dark"><?= $row['total'] ?></span></td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  <?php endif; ?>
</div>

<?php require_once BASE_PATH . '/backend/includes/footer.php'; ?>
