<?php
require_once __DIR__ . '/../../../config/config.php';
require_once __DIR__ . '/../../../includes/session.php';
exigir_login('usuario');

$id = $_SESSION['usuario_id'] ?? null;

if ($id) {
    $stmt = $conn->prepare("DELETE FROM usuarios WHERE id = ?");
    $stmt->bind_param("i", $id);
    if ($stmt->execute()) {
        session_unset();
        session_destroy();
        header("Location: " . URL_BASE . "login/index.php?msg=conta_excluida");
        exit;
    } else {
        $_SESSION['erro'] = "Erro ao excluir conta.";
    }
} else {
    $_SESSION['erro'] = "ID de usuário inválido.";
}

header("Location: " . URL_BASE . "usuario/perfil.php");
exit;
