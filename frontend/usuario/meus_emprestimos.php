<?php
// Caminho: frontend/usuario/meus_emprestimos.php

define('BASE_PATH', dirname(__DIR__, 2));
require_once BASE_PATH . '/backend/config/config.php';
require_once BASE_PATH . '/backend/includes/db.php';
require_once BASE_PATH . '/backend/includes/session.php';

if (!isset($_SESSION['usuario_id'])) {
    $_SESSION['erro'] = "Você precisa estar logado para ver seus empréstimos.";
    header("Location: " . URL_BASE . "frontend/login/login.php");
    exit;
}

$usuario_id = $_SESSION['usuario_id'];

// 📚 Empréstimos ativos
$stmtEmp = $pdo->prepare("
    SELECT e.*, l.titulo, l.capa_local, l.capa_url
    FROM emprestimos e
    JOIN livros l ON e.livro_id = l.id
    WHERE e.usuario_id = ? AND e.status = 'emprestado'
    ORDER BY e.data_emprestimo DESC
");
$stmtEmp->execute([$usuario_id]);
$emprestimos = $stmtEmp->fetchAll();

// 🔖 Reservas pendentes ou confirmadas
$stmtRes = $pdo->prepare("
    SELECT r.*, l.titulo, l.capa_local, l.capa_url
    FROM reservas r
    JOIN livros l ON r.livro_id = l.id
    WHERE r.usuario_id = ? AND r.status IN ('pendente', 'confirmada')
    ORDER BY r.data_reserva DESC
");
$stmtRes->execute([$usuario_id]);
$reservas = $stmtRes->fetchAll();

function capaLivro(array $livro): string {
    if (!empty($livro['capa_local']) && file_exists(BASE_PATH . '/storage/uploads/capas/' . $livro['capa_local'])) {
        return URL_BASE . 'storage/uploads/capas/' . $livro['capa_local'];
    }
    return $livro['capa_url'] ?? (URL_BASE . 'frontend/assets/img/livro_padrao.png');
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <title>Meus Empréstimos - <?= NOME_SISTEMA ?></title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <style>
    .capa {
      width: 60px;
      height: auto;
      border-radius: 4px;
    }
    .table td, .table th {
      vertical-align: middle;
    }
    h5 {
      margin-top: 2rem;
      margin-bottom: 1rem;
    }
    .bg-pendente {
      background-color: #fff3cd;
    }
    .bg-confirmada {
      background-color: #cce5ff;
    }
  </style>
</head>
<body>
<div class="container py-4">
  <h2 class="mb-4"><i class="bi bi-journal-bookmark-fill"></i> Meus Empréstimos e Reservas</h2>

  <!-- Empréstimos -->
  <?php if (count($emprestimos) === 0): ?>
    <div class="alert alert-info"><i class="bi bi-info-circle"></i> Você não possui empréstimos ativos no momento.</div>
  <?php else: ?>
    <h5><i class="bi bi-book"></i> Empréstimos Ativos</h5>
    <div class="table-responsive mb-4">
      <table class="table table-bordered table-striped align-middle">
        <thead class="table-light">
          <tr>
            <th>Livro</th>
            <th>Data do Empréstimo</th>
            <th>Previsão de Devolução</th>
            <th>Status</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($emprestimos as $emp): ?>
            <tr>
              <td>
                <img src="<?= capaLivro($emp) ?>" class="capa me-2" alt="Capa">
                <?= htmlspecialchars($emp['titulo']) ?>
              </td>
              <td><?= date('d/m/Y', strtotime($emp['data_emprestimo'])) ?></td>
              <td><?= date('d/m/Y', strtotime($emp['data_prevista_devolucao'])) ?></td>
              <td><span class="badge bg-success">Emprestado</span></td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  <?php endif; ?>

  <!-- Reservas -->
  <?php if (count($reservas) > 0): ?>
    <h5><i class="bi bi-bookmark-check"></i> Reservas Ativas</h5>
    <div class="table-responsive">
      <table class="table table-bordered table-striped align-middle">
        <thead class="table-light">
          <tr>
            <th>Livro</th>
            <th>Data da Reserva</th>
            <th>Status</th>
            <th>Ações</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($reservas as $res): ?>
            <tr class="<?= $res['status'] === 'confirmada' ? 'bg-confirmada' : 'bg-pendente' ?>">
              <td>
                <img src="<?= capaLivro($res) ?>" class="capa me-2" alt="Capa">
                <?= htmlspecialchars($res['titulo']) ?>
              </td>
              <td><?= date('d/m/Y', strtotime($res['data_reserva'])) ?></td>
              <td>
                <span class="badge <?= $res['status'] === 'confirmada' ? 'bg-primary' : 'bg-warning text-dark' ?>">
                  <?= ucfirst($res['status']) ?>
                </span>
              </td>
              <td>
                <a href="<?= URL_BASE ?>frontend/usuario/cancelar_reserva.php?id=<?= $res['id'] ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Deseja cancelar esta reserva?')">
                  <i class="bi bi-x-circle"></i> Cancelar
                </a>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  <?php endif; ?>

  <a href="index.php" class="btn btn-secondary mt-4"><i class="bi bi-arrow-left"></i> Voltar ao Catálogo</a>
</div>
</body>
</html>
