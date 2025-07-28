<?php
// Caminho: frontend/usuario/solicitar_emprestimo.php

define('BASE_PATH', dirname(__DIR__, 2));
require_once BASE_PATH . '/backend/config/config.php';
require_once BASE_PATH . '/backend/includes/session.php';

if (!isset($_SESSION['usuario_id'])) {
    $_SESSION['erro'] = "Você precisa estar logado.";
    header("Location: " . URL_BASE . "frontend/login/login.php");
    exit;
}

$usuario_id = $_SESSION['usuario_id'];
$livro_id = (int)($_GET['id'] ?? 0);

if ($livro_id <= 0) {
    $_SESSION['erro'] = "Livro inválido.";
    header("Location: " . URL_BASE . "frontend/usuario/livros_disponiveis.php");
    exit;
}

// 🔍 Verifica se o livro está emprestado
$stmt = $pdo->prepare("SELECT COUNT(*) FROM emprestimos WHERE livro_id = ? AND status = 'emprestado'");
$stmt->execute([$livro_id]);
$emprestado = $stmt->fetchColumn() > 0;

if ($emprestado) {
    // 🧠 Verifica se o usuário já reservou esse livro
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM reservas WHERE livro_id = ? AND usuario_id = ? AND status = 'ativa'");
    $stmt->execute([$livro_id, $usuario_id]);
    $ja_reservado = $stmt->fetchColumn() > 0;

    if ($ja_reservado) {
        $_SESSION['erro'] = "Você já possui uma reserva ativa para este livro.";
    } else {
        // 💾 Insere nova reserva
        $stmt = $pdo->prepare("INSERT INTO reservas (usuario_id, livro_id, data_reserva, status) VALUES (?, ?, NOW(), 'ativa')");
        $stmt->execute([$usuario_id, $livro_id]);
        $_SESSION['sucesso'] = "Reserva efetuada com sucesso!";
    }

    header("Location: " . URL_BASE . "frontend/usuario/meus_emprestimos.php");
    exit;
}

// 📚 Se não está emprestado, faz o empréstimo
$data_emprestimo = date('Y-m-d');
$data_prevista = date('Y-m-d', strtotime('+7 days'));

$stmt = $pdo->prepare("
    INSERT INTO emprestimos (livro_id, usuario_id, data_emprestimo, data_prevista_devolucao, status)
    VALUES (?, ?, ?, ?, 'emprestado')
");
$stmt->execute([$livro_id, $usuario_id, $data_emprestimo, $data_prevista]);

$_SESSION['sucesso'] = "Empréstimo realizado com sucesso!";
header("Location: " . URL_BASE . "frontend/usuario/meus_emprestimos.php");
exit;
