<?php
// Caminho: frontend/admin/pages/gerenciar_usuarios.php
session_start();
define('BASE_PATH', dirname(__DIR__, 3) . '/backend');

require_once BASE_PATH . '/config/config.php';
require_once BASE_PATH . '/includes/session.php';
require_once BASE_PATH . '/includes/protect_admin.php';
require_once BASE_PATH . '/includes/header.php';

exigir_login('admin'); // permite 'admin' e 'master'

// 🔎 Filtros de busca
$busca = trim($_GET['busca'] ?? '');
$tipoFiltro = $_GET['tipo'] ?? '';

$sql = "SELECT id, nome, email, tipo FROM usuarios WHERE 1";
$params = [];

if ($busca !== '') {
    $sql .= " AND (nome LIKE :busca OR email LIKE :busca)";
    $params[':busca'] = "%$busca%";
}

if (in_array($tipoFiltro, ['admin', 'usuario', 'master'])) {
    $sql .= " AND tipo = :tipo";
    $params[':tipo'] = $tipoFiltro;
}

$sql .= " ORDER BY tipo DESC, nome ASC";

try {
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $_SESSION['erro'] = "Erro ao buscar usuários: " . $e->getMessage();
    $usuarios = [];
}
?>

<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="mb-0">👥 Usuários do Sistema</h3>
        <a href="adicionar_usuario.php" class="btn btn-success">
            <i class="bi bi-person-plus"></i> Novo Usuário
        </a>
    </div>

    <!-- 🔍 Filtros -->
    <form method="GET" class="row g-2 mb-3">
        <div class="col-md-5">
            <input type="text" name="busca" class="form-control" placeholder="Buscar por nome ou e-mail" value="<?= htmlspecialchars($busca) ?>">
        </div>
        <div class="col-md-3">
            <select name="tipo" class="form-select">
                <option value="">Todos os tipos</option>
                <option value="admin" <?= $tipoFiltro === 'admin' ? 'selected' : '' ?>>Administrador</option>
                <option value="usuario" <?= $tipoFiltro === 'usuario' ? 'selected' : '' ?>>Usuário</option>
                <option value="master" <?= $tipoFiltro === 'master' ? 'selected' : '' ?>>Master</option>
            </select>
        </div>
        <div class="col-md-4 d-flex gap-2">
            <button type="submit" class="btn btn-primary">
                <i class="bi bi-search"></i> Pesquisar
            </button>
            <a href="gerenciar_usuarios.php" class="btn btn-outline-secondary">
                <i class="bi bi-x-lg"></i> Limpar
            </a>
        </div>
    </form>

    <!-- 🔔 Mensagens -->
    <?php if (!empty($_SESSION['sucesso'])): ?>
        <div class="alert alert-success"><?= htmlspecialchars($_SESSION['sucesso']); unset($_SESSION['sucesso']); ?></div>
    <?php elseif (!empty($_SESSION['erro'])): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($_SESSION['erro']); unset($_SESSION['erro']); ?></div>
    <?php endif; ?>

    <!-- 📋 Tabela de usuários -->
    <?php if (count($usuarios) === 0): ?>
        <div class="alert alert-warning text-center">Nenhum usuário encontrado com os filtros aplicados.</div>
    <?php else: ?>
        <div class="table-responsive">
            <table class="table table-bordered table-hover align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Nome</th>
                        <th>E-mail</th>
                        <th>Tipo</th>
                        <th class="text-center">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($usuarios as $usuario): ?>
                        <?php
                        $logado_id = $_SESSION['usuario_id'];
                        $logado_tipo = $_SESSION['usuario_tipo'];
                        $alvo_id = $usuario['id'];
                        $alvo_tipo = $usuario['tipo'];
                        $mesmo_usuario = ($logado_id == $alvo_id);
                        $pode_gerenciar = $logado_tipo === 'master' || $alvo_tipo !== 'master';
                        ?>
                        <tr>
                            <td><?= $alvo_id ?></td>
                            <td><?= htmlspecialchars($usuario['nome']) ?></td>
                            <td><?= htmlspecialchars($usuario['email']) ?></td>
                            <td>
                                <span class="badge bg-<?= 
                                    $alvo_tipo === 'master' ? 'primary' : 
                                    ($alvo_tipo === 'admin' ? 'dark' : 'secondary') ?>">
                                    <?= ucfirst($alvo_tipo) ?>
                                </span>
                            </td>
                            <td class="text-center">
                                <div class="btn-group">
                                    <?php if ($pode_gerenciar): ?>
                                        <a href="editar_usuario.php?id=<?= $alvo_id ?>" class="btn btn-sm btn-warning">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                    <?php else: ?>
                                        <span class="btn btn-sm btn-light text-muted" title="Apenas master pode editar outro master.">
                                            <i class="bi bi-lock"></i>
                                        </span>
                                    <?php endif; ?>

                                    <?php if (!$mesmo_usuario && $pode_gerenciar): ?>
                                        <a href="excluir_usuario.php?id=<?= $alvo_id ?>" class="btn btn-sm btn-danger" onclick="return confirm('Tem certeza que deseja excluir este usuário?')">
                                            <i class="bi bi-trash"></i>
                                        </a>
                                    <?php else: ?>
                                        <span class="btn btn-sm btn-light text-muted" title="<?= $mesmo_usuario ? 'Você não pode excluir a si mesmo.' : 'Apenas master pode excluir outro master.' ?>">
                                            <i class="bi bi-lock"></i>
                                        </span>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>

<?php require_once BASE_PATH . '/includes/footer.php'; ?>
