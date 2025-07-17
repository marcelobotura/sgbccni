<?php
// Caminho: frontend/admin/pages/gerenciar_tags.php

define('BASE_PATH', dirname(__DIR__, 3));
require_once BASE_PATH . '/backend/config/config.php';
require_once BASE_PATH . '/backend/includes/session.php';
require_once BASE_PATH . '/backend/includes/protect_admin.php';
require_once BASE_PATH . '/backend/includes/header.php';
require_once BASE_PATH . '/backend/includes/menu.php';

exigir_login('admin');

$busca = $_GET['busca'] ?? '';

// Agora incluindo os novos tipos também aceitos na tabela `tags`
$tipos = ['autor', 'categoria', 'editora', 'outro', 'volume', 'edicao', 'tipo', 'formato'];
$tags = [];

foreach ($tipos as $tipo) {
    $sql = "SELECT id, nome FROM tags WHERE tipo = :tipo";
    $params = [':tipo' => $tipo];

    if (!empty($busca)) {
        $sql .= " AND nome LIKE :busca";
        $params[':busca'] = "%$busca%";
    }

    $sql .= " ORDER BY nome ASC";
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $tags[$tipo] = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>

<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">🏷️ Gerenciar Tags</h2>
        <a href="adicionar_tag.php" class="btn btn-success">
            <i class="bi bi-plus-circle me-1"></i> Nova Tag
        </a>
    </div>

    <form method="get" class="mb-4">
        <div class="input-group">
            <input type="text" name="busca" class="form-control" placeholder="Buscar por nome da tag" value="<?= htmlspecialchars($busca) ?>">
            <button class="btn btn-primary" type="submit"><i class="bi bi-search"></i> Buscar</button>
        </div>
    </form>

    <?php if (!empty($_SESSION['sucesso'])): ?>
        <div class="alert alert-success"><?= htmlspecialchars($_SESSION['sucesso']); unset($_SESSION['sucesso']); ?></div>
    <?php elseif (!empty($_SESSION['erro'])): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($_SESSION['erro']); unset($_SESSION['erro']); ?></div>
    <?php endif; ?>

    <?php foreach ($tags as $tipo => $lista): ?>
        <div class="mb-5">
            <h5 class="text-capitalize">
                <?= ucfirst($tipo) ?><?= $tipo !== 'outro' ? 's' : '' ?>
                <span class="badge bg-secondary"><?= count($lista) ?></span>
            </h5>

            <div class="table-responsive">
                <table class="table table-bordered table-striped align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th style="width: 60px;">ID</th>
                            <th>Nome</th>
                            <th style="width: 160px;">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($lista)): ?>
                            <tr>
                                <td colspan="3" class="text-center text-muted">Nenhuma tag cadastrada.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($lista as $tag): ?>
                                <tr>
                                    <td><?= (int)$tag['id'] ?></td>
                                    <td><?= htmlspecialchars($tag['nome']) ?></td>
                                    <td>
                                        <a href="editar_tag.php?id=<?= (int)$tag['id'] ?>" class="btn btn-sm btn-outline-secondary">
                                            <i class="bi bi-pencil-fill"></i> Editar
                                        </a>
                                        <a href="<?= URL_BASE ?>backend/controllers/tags/excluir_tag.php?id=<?= (int)$tag['id'] ?>"
                                           class="btn btn-sm btn-outline-danger"
                                           onclick="return confirm('Tem certeza que deseja excluir esta tag?')">
                                            <i class="bi bi-trash-fill"></i> Excluir
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    <?php endforeach; ?>
</div>

<?php require_once BASE_PATH . '/backend/includes/footer.php'; ?>
