<?php
// Caminho: backend/controllers/sugestoes/enviar_sugestao.php

require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../includes/session.php';

exigir_login('usuario');

$usuario_id = $_SESSION['usuario_id'] ?? null;
$mensagem = trim($_POST['mensagem'] ?? '');
$tipo = trim($_POST['tipo'] ?? 'outro');

if (!$usuario_id || empty($mensagem)) {
  $_SESSION['erro'] = "A sugestão não pode estar vazia.";
  header('Location: ' . URL_BASE . 'frontend/usuario/sugestoes.php');
  exit;
}

try {
  $sql = "INSERT INTO sugestoes (usuario_id, tipo, mensagem, data_envio, visualizado) 
          VALUES (:usuario_id, :tipo, :mensagem, NOW(), 0)";
  $stmt = $pdo->prepare($sql);
  $stmt->execute([
    ':usuario_id' => $usuario_id,
    ':tipo' => $tipo,
    ':mensagem' => $mensagem
  ]);

  $_SESSION['sucesso'] = "Sugestão enviada com sucesso!";
} catch (PDOException $e) {
  $_SESSION['erro'] = "Erro ao enviar sugestão: " . $e->getMessage();
}

header('Location: ' . URL_BASE . 'frontend/usuario/sugestoes.php');
exit;
