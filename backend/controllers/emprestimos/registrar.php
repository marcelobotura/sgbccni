<?php
// Caminho: backend/controllers/emprestimos/registrar.php

require_once __DIR__ . '/../../config/config.php';
require_once BASE_PATH . '/backend/includes/db.php';
require_once BASE_PATH . '/backend/includes/session.php';

$livro_id = $_POST['livro_id'] ?? null;
$usuario_id = $_POST['usuario_id'] ?? null;
$data_emprestimo = $_POST['data_emprestimo'] ?? date('Y-m-d');
$data_prevista = $_POST['data_prevista_devolucao'] ?? date('Y-m-d', strtotime('+7 days'));
$observacoes = $_POST['observacoes'] ?? '';

if (!$livro_id || !$usuario_id) {
    $_SESSION['erro'] = "Dados incompletos.";
    header("Location: " . URL_BASE . "frontend/admin/pages/registrar_emprestimo.php");
    exit;
}

// Insere o empréstimo
$stmt = $pdo->prepare("INSERT INTO emprestimos 
    (livro_id, usuario_id, data_emprestimo, data_prevista_devolucao, observacoes) 
    VALUES (?, ?, ?, ?, ?)");
$stmt->execute([$livro_id, $usuario_id, $data_emprestimo, $data_prevista, $observacoes]);

// Atualiza status do livro
$update = $pdo->prepare("UPDATE livros SET status = 'emprestado' WHERE id = ?");
$update->execute([$livro_id]);

$_SESSION['sucesso'] = "Empréstimo registrado com sucesso!";
header("Location: " . URL_BASE . "frontend/admin/pages/emprestimos.php");
exit;
