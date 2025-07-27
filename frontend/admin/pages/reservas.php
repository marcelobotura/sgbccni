<?php
// Caminho: frontend/admin/pages/reservas.php

require_once __DIR__ . '/../../../backend/config/config.php';
require_once BASE_PATH . '/backend/includes/db.php';
require_once BASE_PATH . '/backend/includes/session.php';
require_once BASE_PATH . '/backend/includes/protect_admin.php'; // Protege para admin/master

// 🔒 Se quiser limitar apenas para master:
if ($_SESSION['usuario_tipo'] !== 'master' && $_SESSION['usuario_tipo'] !== 'admin') {
    header("Location: " . URL_BASE . "frontend/login/login.php");
    exit;
}

// 🔎 Consulta reservas com informações do livro e usuário
$sql = "SELECT r.*, 
            u.nome AS usuario_nome, 
            l.titulo AS livro_titulo
        FROM reservas r
        JOIN usuarios u ON r.usuario_id = u.id
        JOIN livros l ON r.livro_id = l.id
        ORDER BY r.data_reserva DESC";

$stmt = $pdo->query($sql);
$reservas = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <title>Reservas de Livros - Admin</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body>
<div class="container mt-4">
  <h2 class="mb-4">📚 Reservas de Livros</h2>

  <?php if (count($reservas) === 0): ?>
    <div class="alert alert-info">Nenhuma reserva encontrada.</div>
  <?php else: ?>
    <div class="table-responsive">
      <table class="table table-bordered table-striped">
        <thead class="table-dark">
          <tr>
            <th>#</th>
            <th>Livro</th>
            <th>Usuário</th>
            <th>Data da Reserva</th>
            <th>Status</th>
          </tr>
        </thead>
        <tbody>
        <?php foreach ($reservas as $r): ?>
          <tr>
            <td><?= $r['id'] ?></td>
            <td><?= htmlspecialchars($r['livro_titulo']) ?></td>
            <td><?= htmlspecialchars($r['usuario_nome']) ?></td>
            <td><?= date('d/m/Y H:i', strtotime($r['data_reserva'])) ?></td>
            <td>
              <?php if ($r['status'] === 'ativa'): ?>
                <span class="badge bg-success">Ativa</span>
              <?php elseif ($r['status'] === 'cancelada'): ?>
                <span class="badge bg-secondary">Cancelada</span>
              <?php elseif ($r['status'] === 'concluida'): ?>
                <span class="badge bg-primary">Concluída</span>
              <?php else: ?>
                <span class="badge bg-warning"><?= htmlspecialchars($r['status']) ?></span>
              <?php endif; ?>
            </td>
          </tr>
        <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  <?php endif; ?>
</div>
</body>
</html>
