<?php
session_start();
define('BASE_PATH', dirname(__DIR__, 3) . '/backend');

require_once BASE_PATH . '/config/config.php';
require_once BASE_PATH . '/includes/session.php';
require_once BASE_PATH . '/includes/protect_admin.php';
require_once BASE_PATH . '/includes/header.php';
require_once BASE_PATH . '/includes/menu.php';

exigir_login('admin');

// 🆔 Verifica o ID
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$id) {
    $_SESSION['erro'] = "ID inválido.";
    header("Location: gerenciar_mensagens.php");
    exit;
}

// 🔍 Busca a mensagem
$stmt = $pdo->prepare("SELECT * FROM mensagens WHERE id = :id");
$stmt->execute([':id' => $id]);
$mensagem = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$mensagem) {
    $_SESSION['erro'] = "Mensagem não encontrada.";
    header("Location: gerenciar_mensagens.php");
    exit;
}

// ✅ Marcar como lida (se ainda não estiver)
if ($mensagem['lida'] == 0) {
    $pdo->prepare("UPDATE mensagens SET lida = 1 WHERE id = :id")->execute([':id' => $id]);
}
?>

<div class="container py-4">
    <h3 class="mb-4"><i class="bi bi-envelope-paper"></i> Visualizar Mensagem</h3>

    <div class="card shadow-sm">
        <div class="card-body">
            <p><strong>📛 Nome:</strong> <?= htmlspecialchars($mensagem['nome']) ?></p>
            <p><strong>📧 E-mail:</strong> <?= htmlspecialchars($mensagem['email']) ?></p>
            <p><strong>📝 Assunto:</strong> <?= htmlspecialchars($mensagem['assunto']) ?></p>
            <p><strong>📅 Data de envio:</strong> <?= date('d/m/Y H:i', strtotime($mensagem['data_envio'])) ?></p>
            <hr>
            <p><strong>✉️ Mensagem:</strong><br><?= nl2br(htmlspecialchars($mensagem['mensagem'])) ?></p>

            <div class="mt-4 text-end">
                <a href="gerenciar_mensagens.php" class="btn btn-secondary">
                    <i class="bi bi-arrow-left"></i> Voltar
                </a>
            </div>
        </div>
    </div>
</div>

<?php require_once BASE_PATH . '/includes/footer.php'; ?>
