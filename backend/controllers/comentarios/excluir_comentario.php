<?php
// backend/controllers/comentarios/excluir_comentario.php
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../includes/session.php';
require_once __DIR__ . '/../../includes/protect_admin.php';

exigir_login('admin');

$id = intval($_GET['id'] ?? 0);

if ($id <= 0) {
    $_SESSION['erro'] = "ID inválido.";
    header('Location: ../../../frontend/admin/pages/gerenciar_comentarios.php');
    exit;
}

try {
    $stmt = $pdo->prepare("DELETE FROM comentarios WHERE id = ?");
    $stmt->execute([$id]);

    $_SESSION['sucesso'] = "Comentário excluído com sucesso!";
} catch (PDOException $e) {
    $_SESSION['erro'] = "Erro ao excluir comentário.";
}

header('Location: ../../../frontend/admin/pages/gerenciar_comentarios.php');
exit;
