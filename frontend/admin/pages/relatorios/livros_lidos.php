<?php
// Caminho: frontend/admin/pages/relatorios/mais_lidos.php

define('BASE_PATH', dirname(__DIR__, 4));
require_once BASE_PATH . '/backend/config/config.php';
require_once BASE_PATH . '/backend/includes/session.php';
require_once BASE_PATH . '/backend/includes/protect_admin.php';
require_once BASE_PATH . '/backend/includes/header.php';
require_once BASE_PATH . '/backend/includes/menu.php';

exigir_login('admin');

$stmt = $pdo->query("SELECT l.titulo, COUNT(*) AS total
  FROM livros_usuarios lu
  JOIN livros l ON lu.livro_id = l.id
  WHERE lu.status = 'lido'
  GROUP BY l.id, l.titulo
  ORDER BY total DESC
  LIMIT 20");
$livros = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<div class="container py-4">
  <h2 class="mb-4"><i class="bi bi-star-fill"></i> Livros Mais Lidos</h2>
  <table class="table table-hover table-bordered">
    <thead class="table-dark">
      <tr>
        <th>#</th>
        <th>Título</th>
        <th>Total de Leituras</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($livros as $index => $livro): ?>
        <tr>
          <td><?= $index + 1 ?></td>
          <td><?= htmlspecialchars($livro['titulo']) ?></td>
          <td><?= $livro['total'] ?></td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>
