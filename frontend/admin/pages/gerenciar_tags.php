<?php
require_once __DIR__ . '/../../../backend/config/config.php';
require_once __DIR__ . '/../../../backend/includes/session.php';
require_once __DIR__ . '/../../../backend/includes/protect_admin.php';
require_once __DIR__ . '/../../../backend/includes/header.php';
require_once __DIR__ . '/../../../backend/includes/menu.php';

exigir_login('admin');

// 🔍 Captura filtro de busca
$busca = $_GET['busca'] ?? '';

// 🔍 Busca de tags por tipo
$tipos = ['autor', 'categoria', 'editora', 'outro'];
$tags = [];

foreach ($tipos as $tipo) {
    if (!empty($busca)) {
        $stmt = $pdo->prepare("SELECT id, nome FROM tags WHERE tipo = :tipo AND nome LIKE :busca ORDER BY nome ASC");
        $stmt->execute([':tipo' => $tipo, ':busca' => "%$busca%"]);
    } else {
        $stmt = $pdo->prepare("SELECT id, nome FROM tags WHERE tipo = :tipo ORDER BY nome ASC");
        $stmt->execute([':tipo' => $tipo]);
    }
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

    <!-- Campo de busca -->
    <form method="get" class="mb-4">
        <div class="input-group">
            <input type="text" name="busca" class="form-control" placeholder="Buscar por nome da tag" value="<?= htmlspecialchars($busca) ?>">
            <button class="btn btn-primary" type="submit"><i class="bi bi-search"></i> Buscar</button>
        </div>
    </form>

    <!-- Mensagens de sucesso/erro -->
    <?php if (!empty($_SESSION['sucesso'])): ?>
        <div class="alert alert-success"><?= htmlspecialchars($_SESSION['sucesso']); unset($_SESSION['sucesso']); ?></div>
    <?php elseif (!empty($_SESSION['erro'])): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($_SESSION['erro']); unset($_SESSION['erro']); ?></div>
    <?php endif; ?>

    <?php foreach ($tags as $tipo => $lista): ?>
        <div class="mb-5">
            <h5 class="text-capitalize">
                <?= ucfirst($tipo) ?>s 
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
                        <?php if (count($lista) === 0): ?>
                            <tr>
                                <td colspan="3" class="text-center text-muted">Nenhuma tag cadastrada.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($lista as $tag): ?>
                                <tr>
                                    <td><?= $tag['id'] ?></td>
                                    <td><?= htmlspecialchars($tag['nome']) ?></td>
                                    <td>
                                        <a href="editar_tag.php?id=<?= $tag['id'] ?>" class="btn btn-sm btn-outline-secondary">
                                            <i class="bi bi-pencil-fill"></i> Editar
                                        </a>
                                        <a href="<?= URL_BASE ?>backend/controllers/tags/excluir_tag.php?id=<?= $tag['id'] ?>" 
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

<?php require_once __DIR__ . '/../../../backend/includes/footer.php'; ?>
