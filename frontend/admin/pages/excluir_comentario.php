<?php
require_once '../../../backend/config/config.php';
require_once '../../../backend/includes/session.php';
require_once '../../../backend/includes/protect_admin.php';

$id = intval($_GET['id'] ?? 0);
if ($id > 0) {
    $stmt = $pdo->prepare("DELETE FROM comentarios WHERE id = ?");
    $stmt->execute([$id]);
    $_SESSION['sucesso'] = "Comentário excluído com sucesso!";
}
header("Location: gerenciar_comentarios.php");
exit;
