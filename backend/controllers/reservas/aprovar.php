<?php
// Caminho: backend/controllers/reservas/aprovar.php

require_once __DIR__ . '/../../../config/config.php';
require_once BASE_PATH . '/backend/includes/session.php';

$id = (int) ($_GET['id'] ?? 0);

if ($id <= 0) {
    $_SESSION['erro'] = "ID de reserva inválido.";
    header("Location: " . URL_BASE . "frontend/admin/pages/reservas.php");
    exit;
}

try {
    // Verifica se a reserva existe e está pendente
    $stmt = $pdo->prepare("SELECT status FROM reservas WHERE id = ?");
    $stmt->execute([$id]);
    $reserva = $stmt->fetch();

    if (!$reserva) {
        $_SESSION['erro'] = "Reserva não encontrada.";
    } elseif ($reserva['status'] !== 'pendente') {
        $_SESSION['erro'] = "A reserva já foi processada.";
    } else {
        // Atualiza para confirmada
        $stmt = $pdo->prepare("UPDATE reservas SET status = 'confirmada' WHERE id = ?");
        $stmt->execute([$id]);
        $_SESSION['sucesso'] = "Reserva confirmada com sucesso.";
    }
} catch (PDOException $e) {
    $_SESSION['erro'] = "Erro ao confirmar reserva: " . $e->getMessage();
}

header("Location: " . URL_BASE . "frontend/admin/pages/reservas.php");
exit;
