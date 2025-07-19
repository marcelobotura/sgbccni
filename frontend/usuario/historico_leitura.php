<?php
require_once __DIR__ . '/../../backend/config/config.php';
require_once __DIR__ . '/../../backend/includes/session.php';
require_once __DIR__ . '/protect_usuario.php';

exigir_login('usuario');
$usuario_id = $_SESSION['usuario_id'];

$sql = "SELECT l.*, lu.data_leitura FROM livros l
        JOIN livros_usuarios lu ON l.id = lu.livro_id
        WHERE lu.usuario_id = :usuario_id AND lu.lido = 1
        ORDER BY lu.data_leitura DESC";

$stmt = $pdo->prepare($sql);
$stmt->execute(['usuario_id' => $usuario_id]);
$historico = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<?php require_once BASE_PATH . '/includes/header.php'; ?>
<div class="container py-4">
    <h2>📖 Histórico de Leitura</h2>

    <?php if (count($historico) === 0): ?>
        <p class="text-muted">Você ainda não marcou livros como lidos.</p>
    <?php else: ?>
        <table class="table table-striped table-bordered">
            <thead class="table-dark">
                <tr>
                    <th>Capa</th>
                    <th>Título</th>
                    <th>ISBN</th>
                    <th>Data de Leitura</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($historico as $livro): ?>
                    <tr>
                        <td style="width: 80px;">
                            <img src="<?= !empty($livro['capa']) ? URL_BASE . 'uploads/capas/' . $livro['capa'] : ($livro['capa_url'] ?: URL_BASE . 'assets/img/sem_capa.png') ?>" class="img-thumbnail" style="height: 60px;">
                        </td>
                        <td><?= htmlspecialchars($livro['titulo']) ?></td>
                        <td><?= htmlspecialchars($livro['isbn']) ?></td>
                        <td><?= date('d/m/Y H:i', strtotime($livro['data_leitura'])) ?></td>
                        <td><a href="livro.php?id=<?= $livro['id'] ?>" class="btn btn-sm btn-primary">Ver</a></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <a href="exportar_historico_pdf.php" class="btn btn-danger mt-3"><i class="bi bi-file-earmark-pdf"></i> Exportar PDF</a>
        <a href="exportar_historico_csv.php" class="btn btn-success mt-3"><i class="bi bi-file-earmark-spreadsheet"></i> Exportar CSV</a>
    <?php endif; ?>
</div>
<?php require_once BASE_PATH . '/includes/footer.php'; ?>
