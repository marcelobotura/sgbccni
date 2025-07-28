<?php
// Caminho: frontend/usuario/meus_reservas.php

define('BASE_PATH', dirname(__DIR__, 2));
require_once BASE_PATH . '/backend/config/config.php';
require_once BASE_PATH . '/backend/includes/session.php';

if (!isset($_SESSION['usuario_id'])) {
    $_SESSION['erro'] = "Você precisa estar logado.";
    header("Location: " . URL_BASE . "frontend/login/login.php");
    exit;
}

$usuario_id = $_SESSION['usuario_id'];

// 🔎 Buscar reservas do usuário
try {
    $stmt = $pdo->prepare("
        SELECT r.id, r.status, r.data_reserva,
               l.titulo AS livro
        FROM reservas r
        JOIN livros l ON r.livro_id = l.id
        WHERE r.usuario_id = ?
        ORDER BY r.data_reserva DESC
    ");
    $stmt->execute([$usuario_id]);
    $reservas = $stmt->fetchAll();
} catch (PDOException $e) {
    $_SESSION['erro'] = "Erro ao carregar reservas: " . $e->getMessage();
    $reservas = [];
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <title>Minhas Reservas - <?= NOME_SISTEMA ?></title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body>
<div class="container mt-4">
  <h2 class="mb-4"><i class="bi bi-bookmark"></i> Minhas Reservas</h2>

  <!-- Mensagens -->
  <?php if (isset($_SESSION['erro'])): ?>
    <div class="alert alert-danger"><?= $_SESSION['erro']; unset($_SESSION['erro']); ?></div>
  <?php endif; ?>

  <?php if (isset($_SESSION['sucesso'])): ?>
    <div class="alert alert-success"><?= $_SESSION['sucesso']; unset($_SESSION['sucesso']); ?></div>
  <?php endif; ?>

  <?php if (empty($reservas)): ?>
    <div class="alert alert-info">Você ainda não fez nenhuma reserva.</div>
  <?php else: ?>
    <table class="table table-bordered table-hover align-middle">
      <thead class="table-light">
        <tr>
          <th>#</th>
          <th>Livro</th>
          <th>Data da Reserva</th>
          <th>Status</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($reservas as $res): ?>
          <tr>
            <td><?= $res['id'] ?></td>
            <td><?= htmlspecialchars($res['livro']) ?></td>
            <td><?= date('d/m/Y H:i', strtotime($res['data_reserva'])) ?></td>
            <td>
              <?php if ($res['status'] === 'pendente'): ?>
                <span class="badge bg-warning text-dark">Pendente</span>
              <?php elseif ($res['status'] === 'confirmada'): ?>
                <span class="badge bg-success">Confirmada</span>
              <?php elseif ($res['status'] === 'cancelada'): ?>
                <span class="badge bg-secondary">Cancelada</span>
              <?php else: ?>
                <span class="badge bg-light text-dark"><?= htmlspecialchars($res['status']) ?></span>
              <?php endif; ?>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  <?php endif; ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
