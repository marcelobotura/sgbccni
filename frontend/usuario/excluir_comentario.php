<?php
// Caminho: frontend/usuario/excluir_comentario.php

define('BASE_PATH', dirname(__DIR__, 2) . '/backend');
require_once BASE_PATH . '/config/config.php';
require_once BASE_PATH . '/includes/session.php';

exigir_login('usuario');

$comentario_id = $_GET['id'] ?? null;
$usuario_id = $_SESSION['usuario_id'] ?? null;

if (!$comentario_id || !$usuario_id) {
    $_SESSION['erro'] = "Comentário inválido.";
    header('Location: comentarios.php');
    exit;
}

// Verifica se pertence ao usuário antes de excluir
$sql = "DELETE FROM comentarios WHERE id = :id AND usuario_id = :usuario_id";
$stmt = $pdo->prepare($sql);
$excluido = $stmt->execute([
    ':id' => $comentario_id,
    ':usuario_id' => $usuario_id
]);

if ($excluido) {
    $_SESSION['sucesso'] = "Comentário excluído com sucesso.";
} else {
    $_SESSION['erro'] = "Erro ao excluir comentário.";
}

header('Location: comentarios.php');
exit;
