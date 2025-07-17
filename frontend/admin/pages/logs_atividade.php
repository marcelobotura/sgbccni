<?php
require_once __DIR__ . '/../../../backend/config/config.php';
require_once __DIR__ . '/../../../backend/includes/header.php';
require_once __DIR__ . '/../../../backend/includes/protect_admin.php'; // exige login admin

// Filtros
$filtro_usuario = trim($_GET['usuario'] ?? '');
$filtro_acao    = trim($_GET['acao'] ?? '');
$filtro_data    = trim($_GET['data'] ?? '');

// Monta SQL dinâmico
$sql = "SELECT * FROM log_atividade WHERE 1=1";
$params = [];

if (!empty($filtro_usuario)) {
    $sql .= " AND usuario LIKE :usuario";
    $params[':usuario'] = "%$filtro_usuario%";
}
if (!empty($filtro_acao)) {
    $sql .= " AND acao LIKE :acao";
    $params[':acao'] = "%$filtro_acao%";
}
if (!empty($filtro_data)) {
    $sql .= " AND DATE(data_atividade) = :data";
    $params[':data'] = $filtro_data;
}

$sql .= " ORDER BY data_atividade DESC";

try {
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $logs = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "<div class='container mt-4 alert alert-danger'>Erro ao buscar logs: " . htmlspecialchars($e->getMessage()) . "</div>";
    $logs = [];
}
?>

<div class="container py-4">
    <h3 class="mb-4">📝 Logs de Atividades</h3>

    <form class="row g-3 mb-4" method="GET">
        <div class="col-md-4">
            <input type="text" name="usuario" class="form-control" placeholder="Filtrar por nome de usuário" value="<?= htmlspecialchars($filtro_usuario) ?>">
        </div>
        <div class="col-md-4">
            <input type="text" name="acao" class="form-control" placeholder="Filtrar por ação realizada" value="<?= htmlspecialchars($filtro_acao) ?>">
        </div>
        <div class="col-md-2">
            <input type="date" name="data" class="form-control" value="<?= htmlspecialchars($filtro_data) ?>">
        </div>
        <div class="col-md-1">
            <button type="submit" class="btn btn-primary w-100">Filtrar</button>
        </div>
        <div class="col-md-1">
            <a href="logs_atividade.php" class="btn btn-secondary w-100">Limpar</a>
        </div>
    </form>

    <div class="table-responsive">
        <table class="table table-striped table-bordered align-middle">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Usuário</th>
                    <th>Ação</th>
                    <th>Data</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($logs) > 0): ?>
                    <?php foreach ($logs as $log): ?>
                        <tr>
                            <td><?= $log['id'] ?></td>
                            <td><?= htmlspecialchars($log['usuario']) ?></td>
                            <td><?= htmlspecialchars($log['acao']) ?></td>
                            <td><?= date('d/m/Y H:i', strtotime($log['data_atividade'])) ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="4" class="text-center text-muted">Nenhuma atividade registrada.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once __DIR__ . '/../../../backend/includes/footer.php'; ?>
