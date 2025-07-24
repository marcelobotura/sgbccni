<?php
// Caminho: frontend/usuario/emprestar_livro.php
require_once __DIR__ . '/../../backend/config/config.php';
require_once BASE_PATH . '/backend/includes/session.php';
require_once BASE_PATH . '/backend/includes/db.php';

exigir_login('usuario');

$livro_id = (int)($_GET['id'] ?? 0);
$usuario_id = $_SESSION['usuario_id'] ?? 0;

if ($livro_id <= 0 || $usuario_id <= 0) {
  $_SESSION['erro'] = 'Requisição inválida.';
  header('Location: ../index.php');
  exit;
}

// Verificar se o livro já está emprestado por esse usuário
$stmt = $pdo->prepare("SELECT id FROM livros_usuarios WHERE usuario_id = ? AND livro_id = ? AND status = 'emprestado'");
$stmt->execute([$usuario_id, $livro_id]);
if ($stmt->rowCount() > 0) {
  $_SESSION['erro'] = 'Você já emprestou este livro.';
  header("Location: ver_livro.php?id=$livro_id");
  exit;
}

// Inserir empréstimo
$ip = $_SERVER['REMOTE_ADDR'] ?? 'desconhecido';
$stmt = $pdo->prepare("INSERT INTO livros_usuarios (usuario_id, livro_id, status, ip, data_leitura) VALUES (?, ?, 'emprestado', ?, NOW())");
$stmt->execute([$usuario_id, $livro_id, $ip]);

// Criar log
$stmtLog = $pdo->prepare("INSERT INTO logs_atividade (usuario_id, acao, descricao, ip, criado_em) VALUES (?, 'emprestimo', ?, ?, NOW())");
$descricao = "Empréstimo do livro ID $livro_id";
$stmtLog->execute([$usuario_id, $descricao, $ip]);

$_SESSION['sucesso'] = 'Livro emprestado com sucesso!';
header("Location: ver_livro.php?id=$livro_id");
exit;
?>
