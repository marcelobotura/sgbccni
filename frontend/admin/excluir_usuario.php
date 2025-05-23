<?php
session_start();
define('BASE_PATH', dirname(__DIR__) . '/backend');
require_once BASE_PATH . '/config/config.php';
require_once BASE_PATH . '/includes/session.php';

exigir_login('admin');

// Verifica se ID foi passado
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
  $_SESSION['erro'] = "ID inválido.";
  header("Location: usuarios.php");
  exit;
}

$id = intval($_GET['id']);

// Impede o admin de excluir a si mesmo
if ($_SESSION['usuario_id'] == $id) {
  $_SESSION['erro'] = "Você não pode excluir sua própria conta.";
  header("Location: usuarios.php");
  exit;
}

// Verifica se o usuário existe
$stmt = $conn->prepare("SELECT id FROM usuarios WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows === 0) {
  $_SESSION['erro'] = "Usuário não encontrado.";
  $stmt->close();
  header("Location: usuarios.php");
  exit;
}
$stmt->close();

// Executa exclusão
$stmt = $conn->prepare("DELETE FROM usuarios WHERE id = ?");
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
  $_SESSION['sucesso'] = "Usuário excluído com sucesso.";
} else {
  $_SESSION['erro'] = "Erro ao excluir o usuário.";
}

$stmt->close();
header("Location: usuarios.php");
exit;
