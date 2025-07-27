<?php
// Caminho: frontend/usuario/cancelar_reserva.php

session_start();
require_once __DIR__ . '/../../../backend/config/config.php';
require_once BASE_PATH . '/backend/includes/db.php';
require_once BASE_PATH . '/backend/includes/session.php';

// ⚠️ Verifica se o usuário está logado
if (!isset($_SESSION['usuario_id'])) {
    $_SESSION['erro'] = "É necessário estar logado para cancelar uma reserva.";
    header("Location: " . URL_BASE . "frontend/login/login.php");
    exit;
}

$usuario_id = $_SESSION['usuario_id'];
$reserva_id = (int)($_GET['id'] ?? 0);

// 🚫 ID inválido
if ($reserva_id <= 0) {
    $_SESSION['erro'] = "Reserva inválida.";
    header("Location: " . URL_BASE . "frontend/usuario/meus_emprestimos.php");
    exit;
}

// 🔎 Verifica se a reserva existe, pertence ao usuário e está ativa
try {
    $sql = "SELECT * FROM reservas WHERE id = ? AND usuario_id = ? AND status = 'ativa'";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$reserva_id, $usuario_id]);
    $reserva = $stmt->fetch();

    if (!$reserva) {
        $_SESSION['erro'] = "Reserva não encontrada ou já cancelada.";
        header("Location: " . URL_BASE . "frontend/usuario/meus_emprestimos.php");
        exit;
    }

    // ✅ Atualiza status da reserva
    $update = $pdo->prepare("UPDATE reservas SET status = 'cancelada' WHERE id = ?");
    $update->execute([$reserva_id]);

    $_SESSION['sucesso'] = "Reserva cancelada com sucesso.";
    header("Location: " . URL_BASE . "frontend/usuario/meus_emprestimos.php");
    exit;

} catch (PDOException $e) {
    $_SESSION['erro'] = "Erro ao cancelar a reserva: " . $e->getMessage();
    header("Location: " . URL_BASE . "frontend/usuario/meus_emprestimos.php");
    exit;
}
