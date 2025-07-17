<?php
session_start();
require_once __DIR__ . '/../../config/config.php';        // Garante que $pdo está definido
require_once __DIR__ . '/../../includes/protect_admin.php';

exigir_login('admin');

// Verifica se o ID foi passado
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    $_SESSION['erro'] = 'ID do livro não fornecido ou inválido.';
    header('Location: ' . URL_BASE . 'frontend/admin/pages/listar_livros.php');
    exit;
}

$id = (int) $_GET['id'];

try {
    $stmt = $pdo->prepare("DELETE FROM livros WHERE id = :id");
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();

    if ($stmt->rowCount()) {
        $_SESSION['sucesso'] = '📕 Livro excluído com sucesso.';
    } else {
        $_SESSION['erro'] = '⚠️ Livro não encontrado ou já foi excluído.';
    }

} catch (PDOException $e) {
    $_SESSION['erro'] = '❌ Erro ao excluir o livro: ' . $e->getMessage();
}

header('Location: ' . URL_BASE . 'frontend/admin/pages/listar_livros.php');
exit;
