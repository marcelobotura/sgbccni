<?php
require_once __DIR__ . '/../../../backend/config/config.php';
require_once __DIR__ . '/../../../backend/includes/protect_admin.php';
require_once __DIR__ . '/../../../backend/includes/header.php';
require_once __DIR__ . '/../../../backend/includes/menu.php';

exigir_login('admin');

// 🔎 Buscar livros
try {
    $stmt = $pdo->prepare("SELECT * FROM livros ORDER BY id DESC");
    $stmt->execute();
    $livros = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $_SESSION['erro'] = "Erro ao buscar livros: " . $e->getMessage();
    $livros = [];
}
?>

<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>📚 Gerenciar Livros</h2>
        <a href="cadastrar_livro.php" class="btn btn-success">
            ➕ Novo Livro
        </a>
    </div>

    <?php if (!empty($_SESSION['sucesso'])): ?>
        <div class="alert alert-success"><?= htmlspecialchars($_SESSION['sucesso']); unset($_SESSION['sucesso']); ?></div>
    <?php elseif (!empty($_SESSION['erro'])): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($_SESSION['erro']); unset($_SESSION['erro']); ?></div>
    <?php endif; ?>

    <div class="table-responsive">
        <table id="tabelaLivros" class="table table-bordered table-hover align-middle">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Capa</th>
                    <th>Título</th>
                    <th>ISBN</th>
                    <th>Tipo</th>
                    <th>Formato</th>
                    <th>Código Interno</th>
                    <th>Status</th>
                    <th style="width:220px;">Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($livros)): ?>
                    <tr><td colspan="9" class="text-center">Nenhum livro encontrado.</td></tr>
                <?php else: ?>
                    <?php foreach ($livros as $livro): ?>
                        <tr>
                            <td><?= $livro['id'] ?></td>
                            <td style="width:80px;">
                                <?php if (!empty($livro['capa_local'])): ?>
                                    <img src="<?= URL_BASE . htmlspecialchars($livro['capa_local']) ?>" class="img-thumbnail" style="height:60px;">
                                <?php elseif (!empty($livro['capa_url'])): ?>
                                    <img src="<?= htmlspecialchars($livro['capa_url']) ?>" class="img-thumbnail" style="height:60px;">
                                <?php else: ?>
                                    <span class="text-muted">Sem imagem</span>
                                <?php endif; ?>
                            </td>
                            <td><?= htmlspecialchars($livro['titulo']) ?></td>
                            <td><?= htmlspecialchars($livro['isbn']) ?></td>
                            <td><?= ucfirst($livro['tipo']) ?></td>
                            <td><?= strtoupper($livro['formato']) ?></td>
                            <td><?= htmlspecialchars($livro['codigo_interno']) ?></td>
                            <td>
                                <?php if (isset($livro['status'])): ?>
                                    <span class="badge bg-<?= 
                                        $livro['status'] === 'disponivel' ? 'success' :
                                        ($livro['status'] === 'reservado' ? 'warning' : 'danger') ?>">
                                        <?= ucfirst($livro['status']) ?>
                                    </span>
                                <?php else: ?>
                                    <span class="badge bg-secondary">Não informado</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <a href="editar_livro.php?id=<?= $livro['id'] ?>" class="btn btn-sm btn-warning mb-1">
                                    ✏️ Editar
                                </a>
                                <a href="<?= URL_BASE ?>backend/controllers/livros/excluir_livro.php?id=<?= $livro['id'] ?>" class="btn btn-sm btn-danger mb-1" onclick="return confirm('Deseja realmente excluir este livro?');">
                                    🗑️ Excluir
                                </a>
                                <a href="visualizar_livro.php?id=<?= $livro['id'] ?>" class="btn btn-sm btn-outline-primary mb-1">
                                    🔍 Ver
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- 📊 DataTables -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.bootstrap5.min.css">

<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.bootstrap5.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.print.min.js"></script>

<script>
$(document).ready(function () {
    $('#tabelaLivros').DataTable({
        language: {
            url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/pt-BR.json'
        },
        pageLength: 10,
        dom: 'Bfrtip',
        buttons: [
            { extend: 'copy', text: '📋 Copiar' },
            { extend: 'csv',  text: '📑 CSV' },
            { extend: 'excel',text: '📊 Excel' },
            { extend: 'pdf',  text: '📄 PDF' },
            { extend: 'print',text: '🖨️ Imprimir' }
        ]
    });
});
</script>

<?php require_once __DIR__ . '/../../../backend/includes/footer.php'; ?>
