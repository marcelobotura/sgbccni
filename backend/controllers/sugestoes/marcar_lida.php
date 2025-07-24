<?php
// Caminho: backend/controllers/sugestoes/marcar_como_lida.php

require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../includes/session.php';

exigir_login('admin');

// 🔍 Validar ID
$id = $_GET['id'] ?? null;

if (!$id || !is_numeric($id)) {
    $_SESSION['erro'] = "ID inválido.";
    header('Location: ' . URL_BASE . 'frontend/admin/pages/gerenciar_sugestoes.php');
    exit;
}

// ✅ Marcar sugestão como visualizada
try {
    $stmt = $pdo->prepare("UPDATE sugestoes SET visualizado = 1 WHERE id = ?");
    $stmt->execute([$id]);

    $_SESSION['sucesso'] = "Sugestão marcada como visualizada.";
} catch (PDOException $e) {
    $_SESSION['erro'] = "Erro ao atualizar sugestão: " . $e->getMessage();
}

header('Location: ' . URL_BASE . 'frontend/admin/pages/gerenciar_sugestoes.php');
exit;
