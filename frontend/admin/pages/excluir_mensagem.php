<?php
session_start();

// ✅ Definição correta do caminho raiz
define('BASE_PATH', dirname(__DIR__, 2));

// 🔐 Proteção
require_once BASE_PATH . '/config/config.php';
require_once BASE_PATH . '/includes/session.php';
require_once BASE_PATH . '/includes/verifica_admin.php';
require_once BASE_PATH . '/includes/protect_admin.php';

exigir_login('admin');

// 🔒 Validação do ID
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$id) {
    $_SESSION['erro'] = "ID inválido.";
    header("Location: ../../../frontend/admin/pages/mensagens.php");
    exit;
}

// 🔍 Verifica se a mensagem existe
try {
    $stmt = $conn->prepare("SELECT id FROM mensagens_contato WHERE id = ?");
    $stmt->execute([$id]);

    if ($stmt->rowCount() === 0) {
        $_SESSION['erro'] = "Mensagem não encontrada.";
        header("Location: ../../../frontend/admin/pages/mensagens.php");
        exit;
    }

    // 🗑️ Executa a exclusão
    $del = $conn->prepare("DELETE FROM mensagens_contato WHERE id = ?");
    if ($del->execute([$id])) {
        $_SESSION['sucesso'] = "✅ Mensagem excluída com sucesso.";
    } else {
        $_SESSION['erro'] = "❌ Erro ao excluir mensagem.";
    }

} catch (PDOException $e) {
    $_SESSION['erro'] = "Erro: " . $e->getMessage();
}

header("Location: ../../../frontend/admin/pages/mensagens.php");
exit;
?>
