<?php
// Caminho: frontend/usuario/reservar_livro.php

session_start();
require_once __DIR__ . '/../../../backend/config/config.php';
require_once BASE_PATH . '/backend/includes/db.php';
require_once BASE_PATH . '/backend/includes/session.php';

// ⚠️ Verifica se o usuário está logado
if (!isset($_SESSION['usuario_id'])) {
    $_SESSION['erro'] = "Você precisa estar logado para reservar um livro.";
    header("Location: " . URL_BASE . "frontend/login/login.php");
    exit;
}

$usuario_id = $_SESSION['usuario_id'];
$livro_id = (int)($_GET['id'] ?? 0);

if ($livro_id <= 0) {
    $_SESSION['erro'] = "Livro inválido.";
    header("Location: " . URL_BASE . "index.php");
    exit;
}

// Verifica se o livro está emprestado
$stmt = $pdo->prepare("SELECT COUNT(*) FROM emprestimos WHERE livro_id = ? AND status = 'emprestado'");
$stmt->execute([$livro_id]);
$emprestado = $stmt->fetchColumn() > 0;

if (!$emprestado) {
    $_SESSION['erro'] = "Este livro não está emprestado no momento e pode ser retirado diretamente.";
    header("Location: " . URL_BASE . "frontend/usuario/meus_emprestimos.php");
    exit;
}

// Verifica se já existe uma reserva ativa para o mesmo livro
$stmt = $pdo->prepare("SELECT id FROM reservas WHERE livro_id = ? AND usuario_id = ? AND status = 'ativa'");
$stmt->execute([$livro_id, $usuario_id]);
$reserva_existente = $stmt->fetch();

if ($reserva_existente) {
    $_SESSION['erro'] = "Você já fez uma reserva para este livro.";
    header("Location: " . URL_BASE . "frontend/usuario/meus_emprestimos.php");
    exit;
}

// Insere a nova reserva
$stmt = $pdo->prepare("INSERT INTO reservas (usuario_id, livro_id, data_reserva, status) VALUES (?, ?, NOW(), 'ativa')");
$stmt->execute([$usuario_id, $livro_id]);

$_SESSION['sucesso'] = "Livro reservado com sucesso!";
header("Location: " . URL_BASE . "frontend/usuario/meus_emprestimos.php");
exit;
