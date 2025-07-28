<?php
// Caminho: backend/controllers/reservas/cancelar.php

require_once __DIR__ . '/../../../config/config.php';
require_once BASE_PATH . '/backend/includes/session.php';

$id = (int) ($_GET['id'] ?? 0);

if ($id <= 0) {
    $_SESSION['erro'] = "ID de reserva inválido.";
    header("Location: " . URL_BASE . "frontend/admin/pages/reservas.php");
    exit;
}

try {
    $stmt = $pdo->prepare("SELECT status FROM reservas WHERE id = ?");
    $stmt->execute([$id]);
    $reserva = $stmt->fetch();

    if (!$reserva) {
        $_SESSION['erro'] = "Reserva não encontrada.";
    } elseif ($reserva['status'] === 'cancelada') {
        $_SESSION['erro'] = "A reserva já está cancelada.";
    } else {
        $stmt = $pdo->prepare("UPDATE reservas SET status = 'cancelada' WHERE id = ?");
        $stmt->execute([$id]);
        $_SESSION['sucesso'] = "Reserva cancelada com sucesso.";
    }
} catch (PDOException $e) {
    $_SESSION['erro'] = "Erro ao cancelar reserva: " . $e->getMessage();
}

header("Location: " . URL_BASE . "frontend/admin/pages/reservas.php");
exit;
