<?php
require_once __DIR__ . '/../../../config/config.php';
require_once __DIR__ . '/../../../includes/session.php';
exigir_login('usuario');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    $_SESSION['erro'] = "Requisição inválida.";
    header("Location: " . URL_BASE . "usuario/perfil.php");
    exit;
}

$id = $_SESSION['usuario_id'] ?? null;

if ($id) {
    // Apaga dados relacionados
    $conn->query("DELETE FROM livros_usuarios WHERE usuario_id = $id");
    $conn->query("DELETE FROM tokens_recuperacao WHERE usuario_id = $id");

    // Exclui o usuário
    $stmt = $conn->prepare("DELETE FROM usuarios WHERE id = ?");
    $stmt->bind_param("i", $id);
    
    if ($stmt->execute()) {
        $_SESSION = [];
        session_unset();
        session_destroy();
        header("Location: " . URL_BASE . "login/index.php?msg=conta_excluida");
        exit;
    } else {
        $_SESSION['erro'] = "Erro ao excluir conta: " . $stmt->error;
    }
} else {
    $_SESSION['erro'] = "ID de usuário inválido.";
}

header("Location: " . URL_BASE . "usuario/perfil.php");
exit;
