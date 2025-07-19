<?php
// Caminho: backend/controllers/comentarios/aprovar_comentario.php

require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../includes/session.php';
require_once __DIR__ . '/../../includes/protect_admin.php';

exigir_login('admin');

$id = intval($_GET['id'] ?? 0);

if ($id <= 0) {
    $_SESSION['erro'] = "ID inválido.";
    header('Location: ' . URL_BASE . 'frontend/admin/pages/gerenciar_comentarios.php');
    exit;
}

try {
    $stmt = $pdo->prepare("UPDATE comentarios SET aprovado = 1 WHERE id = ?");
    $stmt->execute([$id]);

    if ($stmt->rowCount() > 0) {
        $_SESSION['sucesso'] = "✅ Comentário aprovado com sucesso!";
    } else {
        $_SESSION['erro'] = "Nenhum comentário foi atualizado. Verifique o ID.";
    }
} catch (PDOException $e) {
    $_SESSION['erro'] = "Erro ao aprovar comentário: " . $e->getMessage();
}

// Redirecionamento seguro
header('Location: ' . URL_BASE . 'frontend/admin/pages/gerenciar_comentarios.php');
exit;
