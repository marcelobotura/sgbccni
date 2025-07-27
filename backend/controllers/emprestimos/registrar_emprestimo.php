<?php
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../includes/verifica_admin.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $livro_id = $_POST['livro_id'];
    $usuario_id = $_POST['usuario_id'];
    $data_emprestimo = date('Y-m-d');
    $data_prevista = $_POST['data_prevista'];
    $observacao = trim($_POST['observacao']);

    try {
        $stmt = $pdo->prepare("INSERT INTO emprestimos 
            (livro_id, usuario_id, data_emprestimo, data_prevista_devolucao, observacao) 
            VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$livro_id, $usuario_id, $data_emprestimo, $data_prevista, $observacao]);

        $_SESSION['sucesso'] = "Empréstimo registrado com sucesso.";
    } catch (Exception $e) {
        $_SESSION['erro'] = "Erro ao registrar: " . $e->getMessage();
    }

    header("Location: " . URL_BASE . "frontend/admin/pages/emprestimos.php");
    exit;
}
