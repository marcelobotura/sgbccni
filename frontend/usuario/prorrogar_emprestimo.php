<?php
require_once '../../backend/config/config.php';
require_once '../../backend/includes/session.php';

if (!isset($_SESSION['usuario_id'])) {
    header("Location: " . URL_BASE . "frontend/login/login.php");
    exit;
}

$usuario_id = $_SESSION['usuario_id'];
$emprestimo_id = intval($_GET['id'] ?? 0);

// Buscar o empréstimo
$stmt = $pdo->prepare("SELECT * FROM emprestimos WHERE id = ? AND usuario_id = ?");
$stmt->execute([$emprestimo_id, $usuario_id]);
$e = $stmt->fetch();

if (!$e || $e['status'] !== 'emprestado') {
    $_SESSION['erro'] = "Empréstimo inválido ou já devolvido.";
    header("Location: meus_emprestimos.php");
    exit;
}

if ($e['renovacoes'] >= 2) {
    $_SESSION['erro'] = "Limite de prorrogações atingido.";
    header("Location: meus_emprestimos.php");
    exit;
}

// Calcular nova data prevista
$nova_data = date('Y-m-d', strtotime($e['data_prevista_devolucao'] . ' +7 days'));

$stmt = $pdo->prepare("UPDATE emprestimos SET data_prevista_devolucao = ?, renovacoes = renovacoes + 1, ultima_prorrogacao = NOW() WHERE id = ?");
$stmt->execute([$nova_data, $emprestimo_id]);

$_SESSION['sucesso'] = "Prazo prorrogado com sucesso até " . date('d/m/Y', strtotime($nova_data));
header("Location: meus_emprestimos.php");
exit;
