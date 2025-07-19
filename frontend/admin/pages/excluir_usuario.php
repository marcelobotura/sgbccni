<?php
session_start();
define('BASE_PATH', dirname(__DIR__, 3) . '/backend');

require_once BASE_PATH . '/config/config.php';
require_once BASE_PATH . '/includes/session.php';
require_once BASE_PATH . '/includes/protect_admin.php';

exigir_login('admin');

// 🔍 Validação do ID
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$id) {
    $_SESSION['erro'] = "ID inválido.";
    header("Location: gerenciar_usuarios.php");
    exit;
}

// 🚫 Impede exclusão da própria conta
if ($_SESSION['usuario_id'] == $id) {
    $_SESSION['erro'] = "Você não pode excluir sua própria conta.";
    header("Location: gerenciar_usuarios.php");
    exit;
}

try {
    // 🔎 Confirma se o usuário existe
    $stmt = $pdo->prepare("SELECT id FROM usuarios WHERE id = :id");
    $stmt->execute([':id' => $id]);

    if ($stmt->rowCount() === 0) {
        $_SESSION['erro'] = "Usuário não encontrado.";
        header("Location: gerenciar_usuarios.php");
        exit;
    }

    // ✅ Exclui diretamente (ON DELETE CASCADE cuida do resto)
    $stmt = $pdo->prepare("DELETE FROM usuarios WHERE id = :id");
    $stmt->execute([':id' => $id]);

    $_SESSION['sucesso'] = "✅ Usuário excluído com sucesso.";
} catch (Exception $e) {
    $_SESSION['erro'] = "❌ Erro ao excluir usuário: " . $e->getMessage();
}

// ✅ Redirecionamento correto
header("Location: gerenciar_usuarios.php");
exit;
?>
