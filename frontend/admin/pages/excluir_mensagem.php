<?php
session_start();
define('BASE_PATH', dirname(__DIR__, 3) . '/backend');

require_once BASE_PATH . '/config/config.php';
require_once BASE_PATH . '/includes/session.php';
require_once BASE_PATH . '/includes/protect_admin.php';

exigir_login('admin');

// 🔍 Valida o ID da mensagem
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$id) {
    $_SESSION['erro'] = "ID inválido.";
    header("Location: " . URL_BASE . "frontend/admin/pages/gerenciar_mensagens.php");
    exit;
}

try {
    // 🔍 Verifica se a mensagem existe
    $stmt = $pdo->prepare("SELECT id FROM mensagens WHERE id = :id");
    $stmt->execute([':id' => $id]);

    if ($stmt->rowCount() === 0) {
        $_SESSION['erro'] = "Mensagem não encontrada.";
    } else {
        // 🔥 Exclui a mensagem
        $stmt = $pdo->prepare("DELETE FROM mensagens WHERE id = :id");
        $stmt->execute([':id' => $id]);
        $_SESSION['sucesso'] = "✅ Mensagem excluída com sucesso.";
    }
} catch (Exception $e) {
    $_SESSION['erro'] = "❌ Erro ao excluir mensagem: " . $e->getMessage();
}

// 🔁 Redireciona para a página de mensagens
header("Location: " . URL_BASE . "frontend/admin/pages/gerenciar_mensagens.php");
exit;
