<?php
session_start();
define('BASE_PATH', dirname(__DIR__, 3) . '/backend');

require_once BASE_PATH . '/config/config.php';
require_once BASE_PATH . '/includes/session.php';
require_once BASE_PATH . '/includes/protect_admin.php';
require_once BASE_PATH . '/includes/header.php';
require_once BASE_PATH . '/includes/menu.php';

exigir_login('admin');

// Filtros
$busca = $_GET['busca'] ?? '';
$status = $_GET['status'] ?? '';

// Consulta dinâmica
$sql = "SELECT * FROM mensagens WHERE 1";
$params = [];

if (!empty($busca)) {
    $sql .= " AND (nome LIKE :busca OR email LIKE :busca OR assunto LIKE :busca OR mensagem LIKE :busca)";
    $params[':busca'] = "%$busca%";
}

if ($status === 'lida') {
    $sql .= " AND lida = 1";
} elseif ($status === 'nao_lida') {
    $sql .= " AND lida = 0";
}

$sql .= " ORDER BY data_envio DESC";

try {
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $mensagens = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $_SESSION['erro'] = "Erro ao buscar mensagens: " . $e->getMessage();
    $mensagens = [];
}
?>

<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3>📬 Mensagens Recebidas</h3>
    </div>

    <!-- 🔍 Filtro -->
    <form method="GET" class="row g-2 mb-3">
        <div class="col-md-6">
            <input type="text" name="busca" class="form-control" placeholder="Buscar por nome, e-mail ou assunto"
                   value="<?= htmlspecialchars($busca, ENT_QUOTES) ?>">
        </div>
        <div class="col-md-3">
            <select name="status" class="form-select">
                <option value="">Todas</option>
                <option value="nao_lida" <?= $status === 'nao_lida' ? 'selected' : '' ?>>Não lidas</option>
                <option value="lida" <?= $status === 'lida' ? 'selected' : '' ?>>Lidas</option>
            </select>
        </div>
        <div class="col-md-3 d-flex gap-2">
            <button class="btn btn-primary"><i class="bi bi-search"></i> Pesquisar</button>
            <a href="gerenciar_mensagens.php" class="btn btn-outline-secondary"><i class="bi bi-x-lg"></i> Limpar</a>
        </div>
    </form>

    <!-- ✅ Mensagens de feedback -->
    <?php if (!empty($_SESSION['sucesso'])): ?>
        <div class="alert alert-success"><?= htmlspecialchars($_SESSION['sucesso'], ENT_QUOTES); unset($_SESSION['sucesso']); ?></div>
    <?php elseif (!empty($_SESSION['erro'])): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($_SESSION['erro'], ENT_QUOTES); unset($_SESSION['erro']); ?></div>
    <?php endif; ?>

    <?php if (count($mensagens) === 0): ?>
        <div class="alert alert-warning">Nenhuma mensagem encontrada com os filtros aplicados.</div>
    <?php else: ?>
        <div class="table-responsive">
            <table class="table table-striped table-bordered align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>Nome</th>
                        <th>Email</th>
                        <th>Celular</th>
                        <th>Assunto</th>
                        <th>Mensagem</th>
                        <th>Enviada em</th>
                        <th>Status</th>
                        <th class="text-center">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($mensagens as $msg): ?>
                        <tr>
                            <td><?= htmlspecialchars($msg['nome'], ENT_QUOTES) ?></td>
                            <td><?= htmlspecialchars($msg['email'], ENT_QUOTES) ?></td>
                            <td><?= htmlspecialchars($msg['celular'] ?? '-', ENT_QUOTES) ?></td>
                            <td><?= htmlspecialchars($msg['assunto'] ?? '(Sem assunto)', ENT_QUOTES) ?></td>
                            <td><?= nl2br(htmlspecialchars(mb_strimwidth($msg['mensagem'], 0, 100, '...'), ENT_QUOTES)) ?></td>
                            <td><?= date('d/m/Y H:i', strtotime($msg['data_envio'])) ?></td>
                            <td>
                                <span class="badge bg-<?= $msg['lida'] ? 'secondary' : 'warning' ?>">
                                    <?= $msg['lida'] ? 'Lida' : 'Não lida' ?>
                                </span>
                            </td>
                            <td class="text-center">
                                <a href="ver_mensagem.php?id=<?= $msg['id'] ?>" class="btn btn-sm btn-info" title="Ver">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="<?= URL_BASE ?>backend/controllers/mensagens/excluir_mensagem.php?id=<?= $msg['id'] ?>"
                                   class="btn btn-sm btn-danger"
                                   onclick="return confirm('Tem certeza que deseja excluir esta mensagem?')"
                                   title="Excluir">
                                   <i class="bi bi-trash"></i>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>

<?php require_once BASE_PATH . '/includes/footer.php'; ?>
