<?php
require_once __DIR__ . '/../../../backend/config/config.php';
require_once BASE_PATH . '/backend/includes/verifica_usuario.php';

$usuario_id = $_SESSION['usuario_id'];

if (!isset($_GET['id'])) {
    $_SESSION['erro'] = "Empréstimo inválido.";
    header("Location: meus_emprestimos.php");
    exit;
}

$id = (int) $_GET['id'];
$stmt = $pdo->prepare("SELECT * FROM emprestimos WHERE id = ? AND usuario_id = ? AND status = 'emprestado'");
$stmt->execute([$id, $usuario_id]);
$emprestimo = $stmt->fetch();

if (!$emprestimo) {
    $_SESSION['erro'] = "Empréstimo não encontrado ou não permitido.";
    header("Location: meus_emprestimos.php");
    exit;
}

if ($emprestimo['renovacoes'] >= 2) {
    $_SESSION['erro'] = "Limite de renovações atingido.";
    header("Location: meus_emprestimos.php");
    exit;
}

// Adiciona +7 dias à data prevista
$nova_data = date('Y-m-d', strtotime($emprestimo['data_prevista_devolucao'] . ' +7 days'));

$stmt = $pdo->prepare("UPDATE emprestimos 
    SET data_prevista_devolucao = ?, renovacoes = renovacoes + 1, ultima_prorrogacao = NOW() 
    WHERE id = ?");
$stmt->execute([$nova_data, $id]);

$_SESSION['sucesso'] = "Empréstimo renovado até " . date('d/m/Y', strtotime($nova_data));
header("Location: meus_emprestimos.php");
exit;
