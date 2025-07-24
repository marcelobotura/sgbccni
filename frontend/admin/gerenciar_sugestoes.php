<?php
// Caminho: backend/controllers/sugestoes/responder_sugestao.php

require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../includes/session.php';
require_once __DIR__ . '/../../includes/protect_admin.php';

exigir_login('admin');

// Validação dos dados
$id = $_POST['id'] ?? null;
$resposta = trim($_POST['resposta'] ?? '');

if (!$id || empty($resposta)) {
    $_SESSION['erro'] = "ID inválido ou resposta vazia.";
    header("Location: " . URL_BASE . "frontend/admin/pages/gerenciar_sugestoes.php");
    exit;
}

try {
    $stmt = $pdo->prepare("UPDATE sugestoes SET resposta = :resposta, respondido_em = NOW(), visualizado = 1 WHERE id = :id");
    $stmt->execute([
        ':resposta' => $resposta,
        ':id' => $id
    ]);

    $_SESSION['sucesso'] = "Resposta enviada com sucesso!";
} catch (PDOException $e) {
    $_SESSION['erro'] = "Erro ao salvar resposta: " . $e->getMessage();
}

header("Location: " . URL_BASE . "frontend/admin/pages/gerenciar_sugestoes.php");
exit;
