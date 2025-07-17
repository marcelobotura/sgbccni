<?php
require_once __DIR__ . '/../../../backend/includes/protect_admin.php';
require_once __DIR__ . '/../../../backend/config/config.php';
require_once __DIR__ . '/../../../backend/includes/header.php';
require_once __DIR__ . '/../../../backend/includes/menu.php';

exigir_login('admin');

// ✅ Validar ID
$id = intval($_GET['id'] ?? 0);
if ($id <= 0) {
    echo "<div class='container mt-5'><div class='alert alert-danger'>ID inválido.</div></div>";
    require_once __DIR__ . '/../../../backend/includes/footer.php';
    exit;
}

// 🔎 Buscar o livro com joins (tags como nomes)
$sql = "
    SELECT 
        l.*, 
        a.nome AS autor, 
        e.nome AS editora, 
        c.nome AS categoria
    FROM livros l
    LEFT JOIN tags a ON l.autor_id = a.id
    LEFT JOIN tags e ON l.editora_id = e.id
    LEFT JOIN tags c ON l.categoria_id = c.id
    WHERE l.id = :id
";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':id', $id, PDO::PARAM_INT);
$stmt->execute();
$livro = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$livro) {
    echo "<div class='container mt-5'><div class='alert alert-warning'>Livro não encontrado.</div></div>";
    require_once __DIR__ . '/../../../backend/includes/footer.php';
    exit;
}
?>

<div class="container py-4">
    <h2 class="mb-4">📖 Detalhes do Livro</h2>

    <div class="card shadow-lg border-0">
        <div class="row g-0">
            <!-- Capa -->
            <div class="col-md-4">
                <?php if (!empty($livro['capa_url'])): ?>
                    <img src="<?= URL_BASE . htmlspecialchars($livro['capa_url']) ?>" class="img-fluid rounded-start h-100" style="object-fit:cover;" alt="Capa do livro">
                <?php else: ?>
                    <div class="d-flex align-items-center justify-content-center h-100 bg-light text-muted p-4 rounded-start">
                        Sem imagem disponível
                    </div>
                <?php endif; ?>
            </div>

            <!-- Dados -->
            <div class="col-md-8">
                <div class="card-body">
                    <h3><?= htmlspecialchars($livro['titulo']) ?></h3>
                    <?php if (!empty($livro['subtitulo'])): ?>
                        <h5 class="text-muted"><?= htmlspecialchars($livro['subtitulo']) ?></h5>
                    <?php endif; ?>

                    <table class="table table-bordered mt-3">
                        <tr><th>ID</th><td><?= $livro['id'] ?></td></tr>
                        <tr><th>Código Interno</th><td><?= htmlspecialchars($livro['codigo_interno']) ?></td></tr>
                        <tr><th>ISBN (13)</th><td><?= htmlspecialchars($livro['isbn']) ?></td></tr>
                        <tr><th>ISBN-10</th><td><?= htmlspecialchars($livro['isbn10'] ?? '') ?></td></tr>
                        <tr><th>Código de Barras</th><td><?= htmlspecialchars($livro['codigo_barras'] ?? '') ?></td></tr>
                        <tr><th>Tipo</th><td><?= htmlspecialchars(ucfirst($livro['tipo'])) ?></td></tr>
                        <tr><th>Formato</th><td><?= htmlspecialchars($livro['formato']) ?></td></tr>
                        <tr><th>Volume</th><td><?= htmlspecialchars($livro['volume']) ?></td></tr>
                        <tr><th>Edição</th><td><?= htmlspecialchars($livro['edicao']) ?></td></tr>
                        <tr><th>Ano</th><td><?= htmlspecialchars($livro['ano'] ?? '') ?></td></tr>
                        <tr><th>Idioma</th><td><?= htmlspecialchars($livro['idioma'] ?? '') ?></td></tr>
                        <tr><th>Autor</th><td><?= htmlspecialchars($livro['autor'] ?? 'Não informado') ?></td></tr>
                        <tr><th>Editora</th><td><?= htmlspecialchars($livro['editora'] ?? 'Não informada') ?></td></tr>
                        <tr><th>Categoria</th><td><?= htmlspecialchars($livro['categoria'] ?? 'Não informada') ?></td></tr>
                        <tr>
                            <th>Link Digital</th>
                            <td>
                                <?php if (!empty($livro['link_digital'])): ?>
                                    <a href="<?= htmlspecialchars($livro['link_digital']) ?>" target="_blank" class="btn btn-sm btn-success">
                                        📖 Acessar Link
                                    </a>
                                <?php else: ?>
                                    <em>Nenhum</em>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <tr>
                            <th>Descrição</th>
                            <td><?= nl2br(htmlspecialchars($livro['descricao'])) ?></td>
                        </tr>
                        <tr>
                            <th>Fonte dos Dados</th>
                            <td><?= htmlspecialchars($livro['fonte'] ?? 'Manual') ?></td>
                        </tr>
                    </table>

                    <div class="text-center">
                        <a href="listar_livros.php" class="btn btn-outline-primary">← Voltar</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../../../backend/includes/footer.php'; ?>
