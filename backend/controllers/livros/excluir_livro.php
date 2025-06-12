<?php
session_start();
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../includes/protect_admin.php';

exigir_login('admin');

// Verifica se o ID foi passado
if (!isset($_GET['id'])) {
  $_SESSION['erro'] = 'ID do livro não fornecido.';
  header('Location: ' . URL_BASE . 'frontend/admin/pages/listar_livros.php');
  exit;
}

$id = intval($_GET['id']);

try {
  $stmt = $conn->prepare("DELETE FROM livros WHERE id = :id");
  $stmt->bindParam(':id', $id, PDO::PARAM_INT);
  $stmt->execute();

  $_SESSION['sucesso'] = 'Livro excluído com sucesso.';
} catch (PDOException $e) {
  $_SESSION['erro'] = 'Erro ao excluir o livro: ' . $e->getMessage();
}

header('Location: ' . URL_BASE . 'frontend/admin/pages/listar_livros.php');
exit;
