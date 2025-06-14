<?php
session_start();
define('BASE_PATH', dirname(__DIR__, 3) . '/backend');

require_once BASE_PATH . '/config/config.php';
require_once BASE_PATH . '/includes/session.php';
require_once BASE_PATH . '/includes/verifica_admin.php';
require_once BASE_PATH . '/includes/protect_admin.php';

exigir_login('admin');

// 🔍 Verifica se o ID foi enviado corretamente
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$id) {
    $_SESSION['erro'] = "ID inválido.";
    header("Location: usuarios.php");
    exit;
}

// 🚫 Impede o admin de excluir sua própria conta
if ($_SESSION['usuario_id'] == $id) {
    $_SESSION['erro'] = "Você não pode excluir sua própria conta.";
    header("Location: usuarios.php");
    exit;
}

try {
    // 🔎 Verifica se o usuário existe
    $stmt = $conn->prepare("SELECT id FROM usuarios WHERE id = :id");
    $stmt->execute([':id' => $id]);
    if ($stmt->rowCount() === 0) {
        $_SESSION['erro'] = "Usuário não encontrado.";
        header("Location: usuarios.php");
        exit;
    }

    // ✅ Inicia a transação
    $conn->beginTransaction();

    // 🔥 Exclui registros relacionados (se não tiver ON DELETE CASCADE)
    $stmt = $conn->prepare("DELETE FROM livros_usuarios WHERE usuario_id = :id");
    $stmt->execute([':id' => $id]);

    $stmt = $conn->prepare("DELETE FROM tokens_recuperacao WHERE usuario_id = :id");
    $stmt->execute([':id' => $id]);

    // 🔥 Exclui o usuário
    $stmt = $conn->prepare("DELETE FROM usuarios WHERE id = :id");
    $stmt->execute([':id' => $id]);

    // 💾 Commit
    $conn->commit();

    $_SESSION['sucesso'] = "✅ Usuário excluído com sucesso.";
} catch (Exception $e) {
    $conn->rollBack();
    $_SESSION['erro'] = "❌ Erro ao excluir usuário: " . $e->getMessage();
}

header("Location: usuarios.php");
exit;
?>
