<?php
// ✅ Definir BASE_PATH corretamente (2 níveis acima da pasta atual)
define('BASE_PATH', dirname(__DIR__, 2));

// 🔧 Includes essenciais
require_once BASE_PATH . '/backend/config/config.php';
require_once BASE_PATH . '/backend/includes/session.php';
require_once __DIR__ . '/protect_usuario.php';

// 🔒 Verifica login
exigir_login('usuario');
$usuario_id = $_SESSION['usuario_id'];

// 🔎 Buscar livros lidos
$sql = "SELECT l.*, lu.data_leitura FROM livros l
        JOIN livros_usuarios lu ON l.id = lu.livro_id
        WHERE lu.usuario_id = :usuario_id AND lu.lido = 1
        ORDER BY lu.data_leitura DESC";

$stmt = $pdo->prepare($sql);
$stmt->execute(['usuario_id' => $usuario_id]);
$historico = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<?php require_once BASE_PATH . '/backend/includes/header.php'; ?>

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
                            <img src="<?= !empty($livro['capa_local']) && file_exists(BASE_PATH . '/../storage/uploads/capas/' . $livro['capa_local']) 
                                ? URL_BASE . 'storage/uploads/capas/' . $livro['capa_local'] 
                                : (!empty($livro['capa_url']) 
                                    ? $livro['capa_url'] 
                                    : URL_BASE . 'frontend/assets/img/livro_padrao.png') 
                            ?>" class="img-thumbnail" style="height: 60px;">
                        </td>
                        <td><?= htmlspecialchars($livro['titulo']) ?></td>
                        <td><?= htmlspecialchars($livro['isbn']) ?></td>
                        <td>
                          <?= !empty($livro['data_leitura']) 
                                ? date('d/m/Y H:i', strtotime($livro['data_leitura'])) 
                                : '<span class="text-muted">Data não registrada</span>' ?>
                        </td>
                        <td>
                          <a href="livro.php?id=<?= $livro['id'] ?>" class="btn btn-sm btn-primary">Ver</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <a href="exportar_historico_pdf.php" class="btn btn-danger mt-3">
          <i class="bi bi-file-earmark-pdf"></i> Exportar PDF
        </a>
        <a href="exportar_historico_csv.php" class="btn btn-success mt-3">
          <i class="bi bi-file-earmark-spreadsheet"></i> Exportar CSV
        </a>
    <?php endif; ?>
</div>

<?php require_once BASE_PATH . '/backend/includes/footer.php'; ?>
