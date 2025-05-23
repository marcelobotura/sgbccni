<?php
session_start();
define('BASE_PATH', dirname(__DIR__) . '/backend');
require_once BASE_PATH . '/config/config.php';
require_once BASE_PATH . '/includes/session.php';

exigir_login('admin');

// Valida ID
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
  $_SESSION['erro'] = "ID inválido.";
  header("Location: mensagens.php");
  exit;
}

$id = intval($_GET['id']);

// Verifica se existe
$stmt = $conn->prepare("SELECT id FROM mensagens_contato WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows === 0) {
  $_SESSION['erro'] = "Mensagem não encontrada.";
  $stmt->close();
  header("Location: mensagens.php");
  exit;
}
$stmt->close();

// Executa exclusão
$stmt = $conn->prepare("DELETE FROM mensagens_contato WHERE id = ?");
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
  $_SESSION['sucesso'] = "Mensagem excluída com sucesso.";
} else {
  $_SESSION['erro'] = "Erro ao excluir mensagem.";
}

$stmt->close();
header("Location: mensagens.php");
exit;
