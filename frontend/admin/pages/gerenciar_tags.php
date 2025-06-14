<?php
require_once __DIR__ . '/../../../backend/includes/verifica_admin.php';
require_once __DIR__ . '/../../../backend/includes/header.php';
require_once __DIR__ . '/../../../backend/includes/menu.php';
require_once __DIR__ . '/../../../backend/config/config.php';
require_once __DIR__ . '/../../../backend/includes/protect_admin.php';

exigir_login('admin');

// 🔍 Busca de tags por tipo
$tipos = ['autor', 'categoria', 'editora', 'outro'];
$tags = [];

foreach ($tipos as $tipo) {
    $stmt = $conn->prepare("SELECT id, nome FROM tags WHERE tipo = :tipo ORDER BY nome ASC");
    $stmt->execute([':tipo' => $tipo]);
    $tags[$tipo] = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>

<div class="container py-4">
    <h2 class="mb-4">🏷️ Gerenciar Tags</h2>

    <?php if (!empty($_SESSION['sucesso'])): ?>
        <div class="alert alert-success"><?= htmlspecialchars($_SESSION['sucesso']); unset($_SESSION['sucesso']); ?></div>
    <?php elseif (!empty($_SESSION['erro'])): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($_SESSION['erro']); unset($_SESSION['erro']); ?></div>
    <?php endif; ?>

    <?php foreach ($tags as $tipo => $lista): ?>
        <div class="mb-5">
            <h5 class="text-capitalize"><?= ucfirst($tipo) ?>s</h5>
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
                                        <a href="editar_tag.php?id=<?= $tag['id'] ?>" class="btn btn-sm btn-secondary">✏️ Editar</a>
                                        <a href="../../../backend/controllers/tags/excluir_tag.php?id=<?= $tag['id'] ?>" 
                                           class="btn btn-sm btn-danger" 
                                           onclick="return confirm('Tem certeza que deseja excluir esta tag?')">🗑️ Excluir</a>
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
