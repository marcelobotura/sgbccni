<?php
require_once __DIR__ . '/../../../backend/includes/verifica_admin.php';
require_once __DIR__ . '/../../../backend/includes/header.php';
require_once __DIR__ . '/../../../backend/includes/protect_admin.php';

exigir_login('admin');

// 🔍 Filtros
$filtro_email = trim($_GET['email'] ?? '');
$filtro_ip    = trim($_GET['ip'] ?? '');
$filtro_data  = trim($_GET['data'] ?? '');

// 🔧 Monta SQL dinâmico
$sql = "SELECT * FROM log_redefinicao_senha WHERE 1=1";
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
    $sql .= " AND DATE(data_redefinicao) = :data";
    $params[':data'] = $filtro_data;
}

$sql .= " ORDER BY data_redefinicao DESC";

// 🔍 Executa consulta
$stmt = $conn->prepare($sql);
$stmt->execute($params);
$resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="container py-4">
    <h3 class="mb-4">📜 Logs de Redefinição de Senha</h3>

    <form class="row g-3 mb-4" method="GET">
        <div class="col-md-3">
            <input type="text" name="email" class="form-control" placeholder="Filtrar por e-mail" value="<?= htmlspecialchars($filtro_email) ?>">
        </div>
        <div class="col-md-2">
            <input type="text" name="ip" class="form-control" placeholder="Filtrar por IP" value="<?= htmlspecialchars($filtro_ip) ?>">
        </div>
        <div class="col-md-3">
            <input type="date" name="data" class="form-control" value="<?= htmlspecialchars($filtro_data) ?>">
        </div>
        <div class="col-md-2">
            <button type="submit" class="btn btn-primary w-100">Filtrar</button>
        </div>
        <div class="col-md-2">
            <a href="logs_redefinicao.php" class="btn btn-secondary w-100">Limpar</a>
        </div>
    </form>

    <div class="table-responsive">
        <table class="table table-striped table-bordered align-middle">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>E-mail</th>
                    <th>IP</th>
                    <th>Navegador</th>
                    <th>Data</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($resultado) > 0): ?>
                    <?php foreach ($resultado as $log): ?>
                        <tr>
                            <td><?= $log['id'] ?></td>
                            <td><?= htmlspecialchars($log['email']) ?></td>
                            <td><?= htmlspecialchars($log['ip']) ?></td>
                            <td><?= htmlspecialchars(substr($log['navegador'], 0, 60)) ?>...</td>
                            <td><?= date('d/m/Y H:i', strtotime($log['data_redefinicao'])) ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="5" class="text-center text-muted">Nenhum log encontrado.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once __DIR__ . '/../../../backend/includes/footer.php'; ?>
