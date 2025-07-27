<?php
// Caminho: frontend/usuario/renovar_emprestimo.php

require_once __DIR__ . '/../../backend/config/config.php';
require_once BASE_PATH . '/backend/includes/db.php';
require_once BASE_PATH . '/backend/includes/session.php';

if (!isset($_SESSION['usuario_id'])) {
  header("Location: " . URL_BASE . "frontend/login/login.php");
  exit;
}

$usuario_id = $_SESSION['usuario_id'];
$emprestimo_id = (int)($_GET['id'] ?? 0);

// 🔒 Busca o empréstimo do usuário
$stmt = $pdo->prepare("SELECT * FROM emprestimos WHERE id = ? AND usuario_id = ?");
$stmt->execute([$emprestimo_id, $usuario_id]);
$emprestimo = $stmt->fetch();

if (!$emprestimo) {
  die('Empréstimo não encontrado ou não autorizado.');
}

$hoje = new DateTime();
$data_prevista = new DateTime($emprestimo['data_prevista_devolucao']);
$ultima_prorrogacao = $emprestimo['ultima_prorrogacao'] ? new DateTime($emprestimo['ultima_prorrogacao']) : null;

// ✅ Verificações
$limite_prorrogacoes = 2;
$renovacoes = (int)$emprestimo['renovacoes'];

if ($emprestimo['status'] !== 'emprestado') {
  die('Este empréstimo não está ativo.');
}

if ($renovacoes >= $limite_prorrogacoes) {
  die('Limite de renovações atingido.');
}

if ($ultima_prorrogacao && $ultima_prorrogacao->diff($hoje)->days < 1) {
  die('Você só pode renovar uma vez por dia.');
}

// 🔁 Calcula nova data de devolução (+7 dias)
$nova_data = $data_prevista->modify('+7 days');
$hoje_str = $hoje->format('Y-m-d');

$stmtUpdate = $pdo->prepare("UPDATE emprestimos SET 
  data_prevista_devolucao = ?, 
  renovacoes = renovacoes + 1, 
  ultima_prorrogacao = ?
  WHERE id = ?");

if ($stmtUpdate->execute([$nova_data->format('Y-m-d'), $hoje_str, $emprestimo_id])) {
  $_SESSION['sucesso'] = "Empréstimo renovado até " . $nova_data->format('d/m/Y');
} else {
  $_SESSION['erro'] = "Erro ao renovar empréstimo.";
}

header("Location: " . URL_BASE . "frontend/usuario/meus_emprestimos.php");
exit;
