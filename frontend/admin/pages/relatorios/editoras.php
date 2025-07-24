<?php
// Caminho: frontend/admin/pages/relatorios/editoras.php

define('BASE_PATH', dirname(__DIR__, 4));
require_once BASE_PATH . '/backend/config/config.php';
require_once BASE_PATH . '/backend/includes/session.php';
require_once BASE_PATH . '/backend/includes/protect_admin.php';
require_once BASE_PATH . '/backend/includes/header.php';
require_once BASE_PATH . '/backend/includes/menu.php';

exigir_login('admin');

// Consulta: Editoras com mais leituras
$sql = "
SELECT t.nome AS editora, COUNT(lu.id) AS total
FROM livros_usuarios lu
JOIN livros l ON l.id = lu.livro_id
JOIN tags t ON t.id = l.editora_id
WHERE lu.status = 'lido'
GROUP BY t.id
ORDER BY total DESC
";
$stmt = $pdo->query($sql);
$editoras = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="container py-4">
  <h2 class="mb-4"><i class="bi bi-building"></i> Relatório de Editoras Mais Lidas</h2>

  <?php if (count($editoras) === 0): ?>
    <div class="alert alert-warning">Nenhuma leitura registrada por editora até o momento.</div>
  <?php else: ?>
    <div class="table-responsive">
      <table class="table table-bordered table-striped align-middle">
        <thead class="table-dark">
          <tr>
            <th>#</th>
            <th>Editora</th>
            <th>Total de Leituras</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($editoras as $i => $row): ?>
            <tr>
              <td><?= $i + 1 ?></td>
              <td><?= htmlspecialchars($row['editora']) ?></td>
              <td><span class="badge bg-primary"><?= $row['total'] ?></span></td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  <?php endif; ?>
</div>

<?php require_once BASE_PATH . '/backend/includes/footer.php'; ?>
