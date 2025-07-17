<?php
session_start();

// Define o caminho base do backend
define('BASE_PATH', dirname(__DIR__, 3) . '/backend');

// Requisitos essenciais
require_once BASE_PATH . '/config/config.php';
require_once BASE_PATH . '/includes/session.php';
require_once BASE_PATH . '/includes/protect_admin.php'; // Verifica se está logado como admin

// Cabeçalho e menu
require_once BASE_PATH . '/includes/header.php';
require_once BASE_PATH . '/includes/menu.php';

// Buscar mensagens
try {
    $stmt = $pdo->prepare("SELECT * FROM mensagens_contato ORDER BY data_envio DESC");
    $stmt->execute();
    $mensagens = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $_SESSION['erro'] = "Erro ao buscar mensagens: " . $e->getMessage();
    $mensagens = [];
}
?>

<div class="container py-4">
    <h2 class="mb-4">📩 Mensagens Recebidas</h2>

    <?php if (!empty($_SESSION['sucesso'])): ?>
        <div class="alert alert-success"><?= htmlspecialchars($_SESSION['sucesso']); unset($_SESSION['sucesso']); ?></div>
    <?php elseif (!empty($_SESSION['erro'])): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($_SESSION['erro']); unset($_SESSION['erro']); ?></div>
    <?php endif; ?>

    <div class="mb-3">
        <a href="<?= URL_BASE ?>frontend/admin/pages/exportar_mensagens_csv.php" class="btn btn-outline-primary me-2">
            <i class="bi bi-file-earmark-spreadsheet"></i> Exportar CSV
        </a>
        <a href="<?= URL_BASE ?>frontend/admin/pages/exportar_mensagens_pdf.php" class="btn btn-outline-danger">
            <i class="bi bi-filetype-pdf"></i> Exportar PDF
        </a>
    </div>

    <?php if (empty($mensagens)): ?>
        <div class="alert alert-info text-center">Nenhuma mensagem de contato foi recebida ainda.</div>
    <?php else: ?>
        <div class="table-responsive">
            <table class="table table-bordered table-hover align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>Nome</th>
                        <th>E-mail</th>
                        <th style="width: 40%;">Mensagem</th>
                        <th>Data</th>
                        <th class="text-center">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($mensagens as $msg): ?>
                        <tr>
                            <td><?= htmlspecialchars($msg['nome']) ?></td>
                            <td>
                                <a href="mailto:<?= htmlspecialchars($msg['email']) ?>" title="Enviar e-mail">
                                    <i class="bi bi-envelope-fill"></i> <?= htmlspecialchars($msg['email']) ?>
                                </a>
                            </td>
                            <td style="max-width: 400px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;" title="<?= htmlspecialchars($msg['mensagem']) ?>">
                                <?= nl2br(htmlspecialchars(mb_strimwidth($msg['mensagem'], 0, 120, '...'))) ?>
                            </td>
                            <td><?= date('d/m/Y H:i', strtotime($msg['data_envio'])) ?></td>
                            <td class="text-center">
                                <a href="<?= URL_BASE ?>backend/controllers/mensagens/excluir_mensagem.php?id=<?= $msg['id'] ?>"
                                   class="btn btn-sm btn-danger"
                                   onclick="return confirm('Deseja realmente excluir esta mensagem?');"
                                   title="Excluir mensagem">
                                    <i class="bi bi-trash-fill"></i>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>

<?php require_once BASE_PATH . '/includes/footer.php'; ?>
