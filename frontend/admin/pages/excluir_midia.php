<?php
// Caminho: frontend/admin/pages/excluir_midia.php

define('BASE_PATH', dirname(__DIR__, 3));
require_once BASE_PATH . '/backend/config/config.php';
require_once BASE_PATH . '/backend/includes/db.php';
require_once BASE_PATH . '/backend/includes/session.php';
require_once BASE_PATH . '/backend/includes/protect_admin.php';

exigir_login('admin');

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($id <= 0) {
  $_SESSION['erro'] = "ID inválido para exclusão.";
  header("Location: listar_midias.php");
  exit;
}

// Buscar mídia para verificar existência da capa local
$stmt = $pdo->prepare("SELECT capa_local FROM midias WHERE id = ?");
$stmt->execute([$id]);
$midia = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$midia) {
  $_SESSION['erro'] = "Mídia não encontrada.";
  header("Location: listar_midias.php");
  exit;
}

// Excluir capa local se existir
if (!empty($midia['capa_local'])) {
  $caminhoCapa = BASE_PATH . '/../storage/uploads/capas/' . $midia['capa_local'];
  if (file_exists($caminhoCapa)) {
    unlink($caminhoCapa);
  }
}

// Excluir registro no banco
try {
  $stmt = $pdo->prepare("DELETE FROM midias WHERE id = ?");
  $stmt->execute([$id]);
  $_SESSION['sucesso'] = "Mídia excluída com sucesso.";
} catch (PDOException $e) {
  $_SESSION['erro'] = "Erro ao excluir mídia: " . $e->getMessage();
}

header("Location: listar_midias.php");
exit;
