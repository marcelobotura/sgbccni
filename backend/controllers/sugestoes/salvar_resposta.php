<?php
// Caminho: backend/controllers/sugestoes/salvar_resposta.php

require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../includes/session.php';
exigir_login('admin');

$id = $_POST['id'] ?? null;
$resposta = trim($_POST['resposta'] ?? '');

if (!$id || empty($resposta)) {
    $_SESSION['erro'] = "Resposta inválida.";
    header("Location: " . URL_BASE . "frontend/admin/pages/gerenciar_sugestoes.php");
    exit;
}

$sql = "UPDATE sugestoes SET resposta = :resposta, respondido_em = NOW(), visualizado = 1 WHERE id = :id";
$stmt = $pdo->prepare($sql);
$salvo = $stmt->execute([':resposta' => $resposta, ':id' => $id]);

$_SESSION[$salvo ? 'sucesso' : 'erro'] = $salvo ? "Resposta enviada." : "Erro ao responder.";
header("Location: " . URL_BASE . "frontend/admin/pages/gerenciar_sugestoes.php");
exit;
