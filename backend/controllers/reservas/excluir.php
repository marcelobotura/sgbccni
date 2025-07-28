<?php
// Caminho: backend/controllers/reservas/excluir.php

require_once __DIR__ . '/../../../config/config.php';
require_once BASE_PATH . '/backend/includes/session.php';

$id = (int) ($_GET['id'] ?? 0);

if ($id <= 0) {
    $_SESSION['erro'] = "ID de reserva inválido.";
    header("Location: " . URL_BASE . "frontend/admin/pages/reservas.php");
    exit;
}

try {
    $stmt = $pdo->prepare("SELECT id FROM reservas WHERE id = ?");
    $stmt->execute([$id]);

    if (!$stmt->fetch()) {
        $_SESSION['erro'] = "Reserva não encontrada.";
    } else {
        $stmt = $pdo->prepare("DELETE FROM reservas WHERE id = ?");
        $stmt->execute([$id]);
        $_SESSION['sucesso'] = "Reserva excluída com sucesso.";
    }
} catch (PDOException $e) {
    $_SESSION['erro'] = "Erro ao excluir reserva: " . $e->getMessage();
}

header("Location: " . URL_BASE . "frontend/admin/pages/reservas.php");
exit;
