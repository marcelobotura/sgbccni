<?php
include_once __DIR__ . '/../../includes/config.php';
require_once __DIR__ . '/../../includes/protect.php';
require_once __DIR__ . '/../../includes/header.php';

// Adicionar ou atualizar tag
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'] ?? null;
    $nome = trim($_POST['nome']);
    $tipo = $_POST['tipo'];

    if ($id) {
        $stmt = $conn->prepare("UPDATE tags SET nome = ?, tipo = ? WHERE id = ?");
        $stmt->bind_param("ssi", $nome, $tipo, $id);
        $stmt->execute();
    } else {
        $stmt = $conn->prepare("INSERT IGNORE INTO tags (nome, tipo) VALUES (?, ?)");
        $stmt->bind_param("ss", $nome, $tipo);
        $stmt->execute();
    }

    header("Location: gerenciar_tags.php");
    exit;
}

// Excluir tag
if (isset($_GET['excluir'])) {
    $idExcluir = intval($_GET['excluir']);
    $conn->query("DELETE FROM tags WHERE id = $idExcluir");
    header("Location: gerenciar_tags.php");
    exit;
}

// Buscar todas as tags separadas por tipo
$tipos = ['autor', 'categoria', 'editora', 'outro'];
$tags = [];

foreach ($tipos as $tipo) {
    $stmt = $conn->prepare("SELECT * FROM tags WHERE tipo = ? ORDER BY nome ASC");
    $stmt->bind_param("s", $tipo);
    $stmt->execute();
    $tags[$tipo] = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
}

// Buscar tag para edição
$tag_editar = null;
if (isset($_GET['editar'])) {
    $idEditar = intval($_GET['editar']);
    $res = $conn->query("SELECT * FROM tags WHERE id = $idEditar");
    $tag_editar = $res->fetch_assoc();
}
?>

<div class="container my-5">
    <h2 class="mb-4">🔖 Gerenciar Tags (Autores, Categorias, Editoras e Outros)</h2>

    <form method="POST" class="row g-3 my-4">
        <input type="hidden" name="id" value="<?= $tag_editar['id'] ?? '' ?>">
        <div class="col-md-6">
            <label class="form-label" style="color: var(--text-color);">Nome da Tag</label>
            <input type="text" name="nome" class="form-control" required value="<?= $tag_editar['nome'] ?? '' ?>">
        </div>
        <div class="col-md-4">
            <label class="form-label" style="color: var(--text-color);">Tipo</label>
            <select name="tipo" class="form-control" required>
                <option value="">Selecione...</option>
                <?php foreach ($tipos as $tipo): ?>
                    <option value="<?= $tipo ?>" <?= ($tag_editar['tipo'] ?? '') === $tipo ? 'selected' : '' ?>><?= ucfirst($tipo) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-md-2 d-flex align-items-end">
            <button type="submit" class="btn btn-success w-100">✅ <?= $tag_editar ? 'Atualizar' : 'Adicionar' ?></button>
        </div>
    </form>

    <?php foreach ($tipos as $tipo): ?>
        <div class="card mb-4">
            <div class="card-header" style="background-color: var(--card-color); color: var(--text-color); border-bottom: 1px solid var(--border-color);">
                <strong><?= ucfirst($tipo) ?>s</strong>
            </div>
            <div class="card-body p-2">
                <table class="table table-sm table-striped m-0" style="color: var(--text-color);">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Nome</th>
                            <th style="width: 130px;">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($tags[$tipo] as $tag): ?>
                            <tr>
                                <td><?= $tag['id'] ?></td>
                                <td><?= htmlspecialchars($tag['nome']) ?></td>
                                <td>
                                    <a href="gerenciar_tags.php?editar=<?= $tag['id'] ?>" class="btn btn-sm btn-warning">✏️</a>
                                    <a href="gerenciar_tags.php?excluir=<?= $tag['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Tem certeza que deseja excluir?')">🗑️</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        <?php if (empty($tags[$tipo])): ?>
                            <tr><td colspan="3"><em>Nenhuma tag cadastrada.</em></td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    <?php endforeach; ?>
</div>

<?php require_once __DIR__ . '/../../includes/footer.php'; ?>
