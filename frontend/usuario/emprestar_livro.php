<?php
define('BASE_PATH', dirname(__DIR__, 2));

require_once BASE_PATH . '/backend/config/config.php';
require_once BASE_PATH . '/backend/includes/db.php';
require_once BASE_PATH . '/backend/includes/session.php';


// ⚠️ Verifica se o usuário está logado
if (!isset($_SESSION['usuario_id'])) {
    $_SESSION['erro'] = "Você precisa estar logado para emprestar um livro.";
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

// Verifica se o livro já está emprestado
$stmt = $pdo->prepare("SELECT COUNT(*) FROM emprestimos WHERE livro_id = ? AND status = 'emprestado'");
$stmt->execute([$livro_id]);
$emprestado = $stmt->fetchColumn() > 0;

if ($emprestado) {
    $_SESSION['erro'] = "Este livro já está emprestado no momento. Você pode fazer uma reserva.";
    header("Location: " . URL_BASE . "frontend/usuario/meus_emprestimos.php");
    exit;
}

// Verifica se o usuário já pegou este livro e ainda não devolveu
$stmt = $pdo->prepare("SELECT COUNT(*) FROM emprestimos WHERE livro_id = ? AND usuario_id = ? AND status = 'emprestado'");
$stmt->execute([$livro_id, $usuario_id]);
$jaPegou = $stmt->fetchColumn() > 0;

if ($jaPegou) {
    $_SESSION['erro'] = "Você já possui este livro emprestado.";
    header("Location: " . URL_BASE . "frontend/usuario/meus_emprestimos.php");
    exit;
}

// Define data de devolução para 7 dias após o empréstimo
$dataEmprestimo = date('Y-m-d');
$dataDevolucao = date('Y-m-d', strtotime('+7 days'));

// Registra o empréstimo
$stmt = $pdo->prepare("INSERT INTO emprestimos (livro_id, usuario_id, data_emprestimo, data_prevista_devolucao, status) VALUES (?, ?, ?, ?, 'emprestado')");
$stmt->execute([$livro_id, $usuario_id, $dataEmprestimo, $dataDevolucao]);

$_SESSION['sucesso'] = "Livro emprestado com sucesso! Devolva até " . date('d/m/Y', strtotime($dataDevolucao)) . ".";
header("Location: " . URL_BASE . "frontend/usuario/meus_emprestimos.php");
exit;
