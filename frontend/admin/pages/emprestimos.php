<?php
require_once __DIR__ . '/../../../backend/config/config.php';
require_once __DIR__ . '/../../../backend/includes/protect_admin.php';
require_once __DIR__ . '/../../../backend/includes/header.php';

// 🔎 Filtros (opcionais)
$status = $_GET['status'] ?? '';
$usuario_id = $_GET['usuario_id'] ?? '';
$hoje = date('Y-m-d');

// 🔁 SQL com filtros
$sql = "SELECT e.*, 
            u.nome AS usuario_nome, 
            l.titulo AS livro_titulo
        FROM emprestimos e
        JOIN usuarios u ON e.usuario_id = u.id
        JOIN livros l ON e.livro_id = l.id
        WHERE 1=1";

$params = [];

if ($status !== '') {
    $sql .= " AND e.status = ?";
    $params[] = $status;
}
if ($usuario_id !== '') {
    $sql .= " AND e.usuario_id = ?";
    $params[] = $usuario_id;
}

$sql .= " ORDER BY e.data_emprestimo DESC";
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$emprestimos = $stmt->fetchAll();

// Carrega todos os usuários para o filtro
$usuarios = $pdo->query("SELECT id, nome FROM usuarios ORDER BY nome")->fetchAll();
?>

<h2 class="mb-4">📋 Lista de Empréstimos</h2>

<form method="GET" class="row g-2 mb-4">
  <div class="col-md-3">
    <label>Status:</label>
    <select name="status" class="form-select">
      <option value="">Todos</option>
      <option value="emprestado" <?= $status == 'emprestado' ? 'selected' : '' ?>>Emprestado</option>
      <option value="devolvido" <?= $status == 'devolvido' ? 'selected' : '' ?>>Devolvido</option>
    </select>
  </div>
  <div class="col-md-4">
    <label>Usuário:</label>
    <select name="usuario_id" class="form-select">
      <option value="">Todos</option>
      <?php foreach ($usuarios as $u): ?>
        <option value="<?= $u['id'] ?>" <?= $usuario_id == $u['id'] ? 'selected' : '' ?>>
          <?= htmlspecialchars($u['nome']) ?>
        </option>
      <?php endforeach; ?>
    </select>
  </div>
  <div class="col-md-3 d-flex align-items-end">
    <button class="btn btn-primary">Filtrar</button>
  </div>
</form>

<div class="table-responsive">
  <table class="table table-bordered table-striped align-middle">
    <thead class="table-dark">
      <tr>
        <th>Livro</th>
        <th>Usuário</th>
        <th>Emprestado em</th>
        <th>Devolver até</th>
        <th>Status</th>
        <th>Dias em atraso</th>
        <th>Multa</th>
        <th>Ação</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($emprestimos as $e): 
        $diasAtraso = 0;
        $multa = 0;

        if ($e['status'] === 'emprestado' && $e['data_prevista_devolucao'] < $hoje) {
            $diasAtraso = (new DateTime($e['data_prevista_devolucao']))->diff(new DateTime($hoje))->days;
            $multa = $diasAtraso * 1.50; // Ex: R$ 1,50 por dia
        }

        $statusBadge = $e['status'] === 'devolvido' ? 'success' : 'warning';
      ?>
        <tr>
          <td><?= htmlspecialchars($e['livro_titulo']) ?></td>
          <td><?= htmlspecialchars($e['usuario_nome']) ?></td>
          <td><?= date('d/m/Y', strtotime($e['data_emprestimo'])) ?></td>
          <td><?= date('d/m/Y', strtotime($e['data_prevista_devolucao'])) ?></td>
          <td><span class="badge bg-<?= $statusBadge ?>"><?= ucfirst($e['status']) ?></span></td>
          <td><?= $diasAtraso ?></td>
          <td>R$ <?= number_format($multa, 2, ',', '.') ?></td>
          <td>
            <?php if ($e['status'] === 'emprestado'): ?>
              <a href="<?= URL_BASE ?>backend/controllers/emprestimos/devolver.php?id=<?= $e['id'] ?>" 
                 class="btn btn-sm btn-outline-success" onclick="return confirm('Confirmar devolução?')">
                 Devolver
              </a>
            <?php else: ?>
              <span class="text-muted">-</span>
            <?php endif; ?>
          </td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>
