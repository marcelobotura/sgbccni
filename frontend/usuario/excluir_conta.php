<?php
define('BASE_PATH', realpath(__DIR__ . '/../../backend'));
require_once BASE_PATH . '/config/config.php';
require_once BASE_PATH . '/includes/session.php';

exigir_login('usuario');

$id = $_SESSION['usuario_id'] ?? 0;

// Verifica se ID é válido
if ($id <= 0) {
    $_SESSION['erro'] = "Usuário inválido.";
    header("Location: ../login/index.php");
    exit;
}

// Exclui do banco de dados
$stmt = $conn->prepare("DELETE FROM usuarios WHERE id = ?");
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    $stmt->close();

    // Destroi sessão
    session_unset();
    session_destroy();

    // Redireciona para login
    header("Location: ../login/index.php?msg=conta_excluida");
    exit;
} else {
    $_SESSION['erro'] = "Erro ao excluir conta.";
    header("Location: ../usuario/configuracoes.php");
    exit;
}
