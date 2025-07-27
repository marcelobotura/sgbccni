<?php
require_once __DIR__ . '/../../../backend/config/config.php';
require_once __DIR__ . '/../../../backend/includes/protect_admin.php';
require_once __DIR__ . '/../../../backend/includes/header.php';

$filtro_status = $_GET['status'] ?? '';
$filtro_usuario = $_GET['usuario'] ?? '';
$filtro_data_inicio = $_GET['data_inicio'] ?? '';
$filtro_data_fim = $_GET['data_fim'] ?? '';

$sql = "SELECT e.*, l.titulo, u.nome AS usuario_nome
        FROM emprestimos e
        JOIN livros l ON e.livro_id = l.id
        JOIN usuarios u ON e.usuario_id = u.id
        WHERE 1=1";

$params = [];

if ($filtro_status) {
    $sql .= " AND e.status = ?";
    $params[] = $filtro_status;
}
if ($filtro_usuario) {
    $sql .= " AND u.nome LIKE ?";
    $params[] = "%$filtro_usuario%";
}
if ($filtro_data_inicio) {
    $sql .= " AND e.data_emprestimo >= ?";
    $params[] = $filtro_data_inicio;
}
if ($filtro_data_fim) {
    $sql .= " AND e.data_emprestimo <= ?";
    $params[] = $filtro_data_fim;
}

$sql .= " ORDER BY e.data_emprestimo DESC";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$emprestimos = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <title>Relatório de Empréstimos - <?= NOME_SISTEMA ?></title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
</head>
<body class="container mt-5">
  <h3>📊 Relatório de Empréstimos</h3>

  <form method="get" class="row g-2 mb-4">
    <div class="col-md-3">
      <label>Status</label>
      <select name="status" class="form-select">
        <option value="">Todos</option>
        <option value="emprestado" <?= $filtro_status === 'emprestado' ? 'selected' : '' ?>>Emprestado</option>
        <option value="devolvido" <?= $filtro_status === 'devolvido' ? 'selected' : '' ?>>Devolvido</option>
      </select>
    </div>
    <div class="col-md-3">
      <label>Usuário</label>
      <input type="text" name="usuario" class="form-control" value="<?= htmlspecialchars($filtro_usuario) ?>">
    </div>
    <div class="col-md-2">
      <label>Data Início</label>
      <input type="date" name="data_inicio" class="form-control" value="<?= htmlspecialchars($filtro_data_inicio) ?>">
    </div>
    <div class="col-md-2">
      <label>Data Fim</label>
      <input type="date" name="data_fim" class="form-control" value="<?= htmlspecialchars($filtro_data_fim) ?>">
    </div>
    <div class="col-md-2 d-flex align-items-end">
      <button class="btn btn-primary w-100" type="submit">Filtrar</button>
    </div>

    <!-- Botões de exportação -->
    <div class="col-md-2 d-flex align-items-end">
      <a href="<?= URL_BASE ?>backend/exportacoes/exportar_emprestimos_pdf.php?<?= http_build_query($_GET) ?>" class="btn btn-danger w-100 me-1">📄 Exportar PDF</a>
    </div>
    <div class="col-md-2 d-flex align-items-end">
      <a href="<?= URL_BASE ?>backend/exportacoes/exportar_emprestimos_csv.php?<?= http_build_query($_GET) ?>" class="btn btn-success w-100">🟩 Exportar CSV</a>
    </div>
  </form>

  <table class="table table-bordered table-striped">
    <thead class="table-dark">
      <tr>
        <th>Livro</th>
        <th>Usuário</th>
        <th>Data Empréstimo</th>
        <th>Data Prevista</th>
        <th>Data Devolução</th>
        <th>Status</th>
        <th>Multa</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($emprestimos as $e): ?>
      <tr>
        <td><?= htmlspecialchars($e['titulo']) ?></td>
        <td><?= htmlspecialchars($e['usuario_nome']) ?></td>
        <td><?= date('d/m/Y', strtotime($e['data_emprestimo'])) ?></td>
        <td><?= date('d/m/Y', strtotime($e['data_prevista_devolucao'])) ?></td>
        <td><?= $e['data_devolucao'] ? date('d/m/Y', strtotime($e['data_devolucao'])) : '-' ?></td>
        <td><span class="badge bg-<?= $e['status'] === 'emprestado' ? 'warning' : 'success' ?>">
          <?= ucfirst($e['status']) ?></span></td>
        <td>R$ <?= number_format($e['multa'], 2, ',', '.') ?></td>
      </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</body>
</html>
