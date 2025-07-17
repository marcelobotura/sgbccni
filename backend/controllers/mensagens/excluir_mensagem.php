<?php
session_start();

require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../includes/session.php';
require_once __DIR__ . '/../../includes/protect_admin.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    $_SESSION['erro'] = "ID inválido.";
    header("Location: " . URL_BASE . "frontend/admin/pages/mensagens.php");
    exit;
}

$id = (int) $_GET['id'];

try {
    $stmt = $pdo->prepare("DELETE FROM mensagens_contato WHERE id = ?");
    $stmt->execute([$id]);

    if ($stmt->rowCount() > 0) {
        $_SESSION['sucesso'] = "Mensagem excluída com sucesso.";
    } else {
        $_SESSION['erro'] = "Mensagem não encontrada ou já foi excluída.";
    }
} catch (PDOException $e) {
    $_SESSION['erro'] = "Erro ao excluir a mensagem: " . $e->getMessage();
}

header("Location: " . URL_BASE . "frontend/admin/pages/mensagens.php");
exit;
