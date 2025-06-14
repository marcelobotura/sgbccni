<?php
session_start();
define('BASE_PATH', dirname(__DIR__, 3) . '/backend');

// 🔐 Proteções e Configurações
require_once BASE_PATH . '/config/config.php';
require_once BASE_PATH . '/includes/session.php';
require_once BASE_PATH . '/includes/verifica_admin.php';
require_once BASE_PATH . '/includes/protect_admin.php';
require_once BASE_PATH . '/includes/header.php';
require_once BASE_PATH . '/includes/menu.php';

exigir_login('admin');

// 🔍 Buscar mensagens do banco
try {
    $stmt = $conn->prepare("SELECT * FROM mensagens_contato ORDER BY data_envio DESC");
    $stmt->execute();
    $mensagens = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $_SESSION['erro'] = "Erro ao buscar mensagens: " . $e->getMessage();
    $mensagens = [];
}
?>

<div class="container py-4">
    <h2 class="mb-4">📩 Mensagens Recebidas</h2>

    <!-- ✅ Mensagens de sucesso ou erro -->
    <?php if (!empty($_SESSION['sucesso'])): ?>
        <div class="alert alert-success"><?= htmlspecialchars($_SESSION['sucesso']); unset($_SESSION['sucesso']); ?></div>
    <?php elseif (!empty($_SESSION['erro'])): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($_SESSION['erro']); unset($_SESSION['erro']); ?></div>
    <?php endif; ?>

    <?php if (count($mensagens) === 0): ?>
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
                            <td><?= nl2br(htmlspecialchars($msg['mensagem'])) ?></td>
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
