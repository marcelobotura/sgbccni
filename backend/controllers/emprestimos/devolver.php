<?php
// Caminho: backend/controllers/autenticacao/login.php

session_start();
require_once __DIR__ . '/../../config/env.php';
require_once __DIR__ . '/../../includes/session.php';
require_once __DIR__ . '/../../config/config.php'; // onde está definido $pd

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: " . URL_BASE . "frontend/admin/pages/emprestimos.php?erro=id_invalido");
    exit;
}

$id = (int) $_GET['id'];

// 🔍 Busca o empréstimo
$stmt = $pdo->prepare("SELECT * FROM emprestimos WHERE id = ?");
$stmt->execute([$id]);
$emprestimo = $stmt->fetch();

if (!$emprestimo) {
    header("Location: " . URL_BASE . "frontend/admin/pages/emprestimos.php?erro=nao_encontrado");
    exit;
}

// 🧮 Calcula atraso e multa
$dataHoje = new DateTime();
$dataPrevista = new DateTime($emprestimo['data_prevista_devolucao']);
$diasAtraso = 0;
$multa = 0.00;

if ($dataHoje > $dataPrevista) {
    $intervalo = $dataHoje->diff($dataPrevista);
    $diasAtraso = $intervalo->days;
    $multa = $diasAtraso * 1.50; // 💰 R$ 1,50 por dia de atraso
}

// ✅ Atualiza o empréstimo como devolvido
$stmt = $pdo->prepare("UPDATE emprestimos 
    SET status = 'devolvido',
        data_devolucao = :hoje,
        dias_atraso = :dias,
        multa = :multa
    WHERE id = :id
");

$stmt->execute([
    ':hoje'  => $dataHoje->format('Y-m-d'),
    ':dias'  => $diasAtraso,
    ':multa' => $multa,
    ':id'    => $id
]);

header("Location: " . URL_BASE . "frontend/admin/pages/emprestimos.php?sucesso=devolvido");
exit;
