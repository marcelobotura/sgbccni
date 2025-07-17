<?php
require_once __DIR__ . '/../../../backend/config/config.php';
require_once __DIR__ . '/../../../backend/includes/header.php';
require_once __DIR__ . '/../../../backend/includes/protect_admin.php';
require_once __DIR__ . '/../../../backend/includes/menu.php';
 // já exige login admin

// Filtros
$filtro_email = trim($_GET['email'] ?? '');
$filtro_ip    = trim($_GET['ip'] ?? '');
$filtro_data  = trim($_GET['data'] ?? '');
$filtro_tipo  = trim($_GET['tipo'] ?? '');

// SQL dinâmico
$sql = "SELECT * FROM log_login WHERE 1=1";
$params = [];

if (!empty($filtro_email)) {
    $sql .= " AND email LIKE :email";
    $params[':email'] = "%$filtro_email%";
}
if (!empty($filtro_ip)) {
    $sql .= " AND ip LIKE :ip";
    $params[':ip'] = "%$filtro_ip%";
}
if (!empty($filtro_data)) {
    $sql .= " AND DATE(data_login) = :data";
    $params[':data'] = $filtro_data;
}
if (!empty($filtro_tipo)) {
    $sql .= " AND tipo_usuario = :tipo";
    $params[':tipo'] = $filtro_tipo;
}

$sql .= " ORDER BY data_login DESC";

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
    <h3 class="mb-4">🔐 Logs de Login</h3>

    <form class="row g-3 mb-4" method="GET">
        <div class="col-md-3">
            <input type="text" name="email" class="form-control" placeholder="Filtrar por e-mail" value="<?= htmlspecialchars($filtro_email) ?>">
        </div>
        <div class="col-md-2">
            <input type="text" name="ip" class="form-control" placeholder="Filtrar por IP" value="<?= htmlspecialchars($filtro_ip) ?>">
        </div>
        <div class="col-md-2">
            <input type="date" name="data" class="form-control" value="<?= htmlspecialchars($filtro_data) ?>">
        </div>
        <div class="col-md-2">
            <select name="tipo" class="form-select">
                <option value="">Todos os tipos</option>
                <option value="admin" <?= $filtro_tipo === 'admin' ? 'selected' : '' ?>>Admin</option>
                <option value="usuario" <?= $filtro_tipo === 'usuario' ? 'selected' : '' ?>>Usuário</option>
            </select>
        </div>
        <div class="col-md-1">
            <button type="submit" class="btn btn-primary w-100">Filtrar</button>
        </div>
        <div class="col-md-2">
            <a href="logs_login.php" class="btn btn-secondary w-100">Limpar</a>
        </div>
    </form>

    <div class="table-responsive">
        <table class="table table-striped table-bordered align-middle">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Email</th>
                    <th>IP</th>
                    <th>Navegador</th>
                    <th>Tipo</th>
                    <th>Data</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($logs) > 0): ?>
                    <?php foreach ($logs as $log): ?>
                        <tr>
                            <td><?= $log['id'] ?></td>
                            <td><?= htmlspecialchars($log['email'] ?? 'N/D') ?></td>
                            <td><?= htmlspecialchars($log['ip'] ?? '-') ?></td>
                            <td><?= htmlspecialchars(substr($log['navegador'] ?? '-', 0, 60)) ?>...</td>
                            <td><?= ucfirst($log['tipo_usuario'] ?? 'indefinido') ?></td>
                            <td><?= date('d/m/Y H:i', strtotime($log['data_login'] ?? '')) ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="6" class="text-center text-muted">Nenhum log encontrado.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once __DIR__ . '/../../../backend/includes/footer.php'; ?>
