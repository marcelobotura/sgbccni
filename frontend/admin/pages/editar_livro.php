<?php
require_once __DIR__ . '/../../../backend/config/config.php';
require_once __DIR__ . '/../../../backend/includes/protect_admin.php';
require_once __DIR__ . '/../../../backend/includes/header.php';
require_once __DIR__ . '/../../../backend/includes/menu.php';

exigir_login('admin');

// 📌 Valida ID
$id = intval($_GET['id'] ?? 0);
if ($id <= 0) {
    $_SESSION['erro'] = 'ID inválido.';
    header('Location: gerenciar_livros.php');
    exit;
}

// 🔍 Busca dados do livro
$stmt = $pdo->prepare("SELECT * FROM livros WHERE id = :id");
$stmt->bindParam(':id', $id);
$stmt->execute();
$livro = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$livro) {
    $_SESSION['erro'] = 'Livro não encontrado.';
    header('Location: gerenciar_livros.php');
    exit;
}

// Função auxiliar
function buscar_nome_tag($pdo, $id) {
    if (!$id) return '';
    $stmt = $pdo->prepare("SELECT nome FROM tags WHERE id = ?");
    $stmt->execute([$id]);
    return $stmt->fetchColumn() ?: '';
}

$autor_nome = buscar_nome_tag($pdo, $livro['autor_id']);
$editora_nome = buscar_nome_tag($pdo, $livro['editora_id']);
$categoria_nome = buscar_nome_tag($pdo, $livro['categoria_id']);
?>

<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<div class="container py-4">
    <h2 class="mb-4">✏️ Editar Livro</h2>

    <?php if (!empty($_SESSION['erro'])): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($_SESSION['erro']); unset($_SESSION['erro']); ?></div>
    <?php elseif (!empty($_SESSION['sucesso'])): ?>
        <div class="alert alert-success"><?= htmlspecialchars($_SESSION['sucesso']); unset($_SESSION['sucesso']); ?></div>
    <?php endif; ?>

    <form action="<?= URL_BASE ?>backend/controllers/livros/salvar_edicao_livro.php" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="id" value="<?= $livro['id'] ?>">

        <div class="row mb-3">
            <div class="col-md-8">
                <label class="form-label">Título *</label>
                <input type="text" name="titulo" class="form-control" value="<?= htmlspecialchars($livro['titulo']) ?>" required>
            </div>
            <div class="col-md-4">
                <label class="form-label">Código Interno *</label>
                <input type="text" name="codigo_interno" class="form-control" value="<?= htmlspecialchars($livro['codigo_interno']) ?>" required>
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-md-4">
                <label class="form-label">ISBN-13</label>
                <input type="text" name="isbn" class="form-control" value="<?= htmlspecialchars($livro['isbn']) ?>">
            </div>
            <div class="col-md-4">
                <label class="form-label">ISBN-10</label>
                <input type="text" name="isbn10" class="form-control" value="<?= htmlspecialchars($livro['isbn10']) ?>">
            </div>
            <div class="col-md-4">
                <label class="form-label">Código de Barras</label>
                <input type="text" name="codigo_barras" class="form-control" value="<?= htmlspecialchars($livro['codigo_barras']) ?>">
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-md-3">
                <label class="form-label">Volume</label>
                <input type="text" name="volume" class="form-control" value="<?= htmlspecialchars($livro['volume']) ?>">
            </div>
            <div class="col-md-3">
                <label class="form-label">Edição</label>
                <input type="text" name="edicao" class="form-control" value="<?= htmlspecialchars($livro['edicao']) ?>">
            </div>
            <div class="col-md-3">
                <label class="form-label">Ano</label>
                <input type="text" name="ano" class="form-control" value="<?= htmlspecialchars($livro['ano']) ?>">
            </div>
            <div class="col-md-3">
                <label class="form-label">Idioma</label>
                <input type="text" name="idioma" class="form-control" value="<?= htmlspecialchars($livro['idioma']) ?>">
            </div>
        </div>

        <div class="mb-3">
            <label class="form-label">Subtítulo</label>
            <input type="text" name="subtitulo" class="form-control" value="<?= htmlspecialchars($livro['subtitulo']) ?>">
        </div>

        <div class="mb-3">
            <label class="form-label">Descrição</label>
            <textarea name="descricao" rows="4" class="form-control"><?= htmlspecialchars($livro['descricao']) ?></textarea>
        </div>

        <div class="row mb-3">
            <div class="col-md-4">
                <label class="form-label">Autor</label>
                <select name="autor_id" class="form-select select2-tags" data-tipo="autor">
                    <option value="<?= $livro['autor_id'] ?>" selected><?= htmlspecialchars($autor_nome) ?></option>
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label">Editora</label>
                <select name="editora_id" class="form-select select2-tags" data-tipo="editora">
                    <option value="<?= $livro['editora_id'] ?>" selected><?= htmlspecialchars($editora_nome) ?></option>
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label">Categoria</label>
                <select name="categoria_id" class="form-select select2-tags" data-tipo="categoria">
                    <option value="<?= $livro['categoria_id'] ?>" selected><?= htmlspecialchars($categoria_nome) ?></option>
                </select>
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-md-6">
                <label class="form-label">Tipo</label>
                <select name="tipo" class="form-select">
                    <option value="físico" <?= $livro['tipo'] === 'físico' ? 'selected' : '' ?>>Físico</option>
                    <option value="digital" <?= $livro['tipo'] === 'digital' ? 'selected' : '' ?>>Digital</option>
                </select>
            </div>
            <div class="col-md-6">
                <label class="form-label">Formato</label>
                <select name="formato" class="form-select">
                    <option value="PDF" <?= $livro['formato'] === 'PDF' ? 'selected' : '' ?>>PDF</option>
                    <option value="EPUB" <?= $livro['formato'] === 'EPUB' ? 'selected' : '' ?>>EPUB</option>
                    <option value="Online" <?= $livro['formato'] === 'Online' ? 'selected' : '' ?>>Online</option>
                </select>
            </div>
        </div>

        <div class="mb-3">
            <label class="form-label">Link Digital</label>
            <input type="url" name="link_digital" class="form-control" value="<?= htmlspecialchars($livro['link_digital']) ?>">
        </div>

        <!-- Capa -->
        <div class="row mb-4">
            <div class="col-md-6">
                <label class="form-label">Capa Atual</label><br>
                <?php if ($livro['origem_capa'] === 'upload' && !empty($livro['capa_local'])): ?>
                    <img src="<?= URL_BASE . htmlspecialchars($livro['capa_local']) ?>" class="img-thumbnail" style="max-height: 200px;">
                <?php elseif ($livro['origem_capa'] === 'url' && !empty($livro['capa_url'])): ?>
                    <img src="<?= htmlspecialchars($livro['capa_url']) ?>" class="img-thumbnail" style="max-height: 200px;">
                <?php else: ?>
                    <span class="text-muted">Nenhuma capa cadastrada.</span>
                <?php endif; ?>
            </div>
            <div class="col-md-6">
                <label class="form-label">Nova Capa (opcional)</label>
                <input type="file" name="nova_capa" class="form-control">
            </div>
        </div>

        <button type="submit" class="btn btn-success">💾 Salvar Alterações</button>
        <a href="gerenciar_livros.php" class="btn btn-secondary ms-2">Cancelar</a>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    $('.select2-tags').each(function () {
        const tipo = $(this).data('tipo');
        $(this).select2({
            theme: 'bootstrap-5',
            tags: true,
            ajax: {
                url: '<?= URL_BASE ?>backend/services/buscar_tags.php',
                dataType: 'json',
                delay: 250,
                data: params => ({ tipo: tipo, q: params.term }),
                processResults: data => ({ results: data }),
                cache: true
            }
        });
    });
});
</script>

<?php require_once __DIR__ . '/../../../backend/includes/footer.php'; ?>
