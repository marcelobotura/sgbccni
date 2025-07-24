<?php
// Caminho: frontend/usuario/salvar_comentario.php

define('BASE_PATH', dirname(__DIR__, 2) . '/backend');
require_once BASE_PATH . '/config/config.php';
require_once BASE_PATH . '/includes/session.php';

exigir_login('usuario');

$comentario_id = $_POST['comentario_id'] ?? null;
$novo_comentario = trim($_POST['comentario'] ?? '');
$usuario_id = $_SESSION['usuario_id'] ?? null;

if (!$comentario_id || !$usuario_id || $novo_comentario === '') {
    $_SESSION['erro'] = "Comentário inválido.";
    header('Location: comentarios.php');
    exit;
}

// Atualiza comentário se pertencer ao usuário
$sql = "UPDATE comentarios SET texto = :texto WHERE id = :id AND usuario_id = :usuario_id";
$stmt = $pdo->prepare($sql);
$atualizado = $stmt->execute([
    ':texto' => $novo_comentario,
    ':id' => $comentario_id,
    ':usuario_id' => $usuario_id
]);

if ($atualizado) {
    $_SESSION['sucesso'] = "Comentário atualizado com sucesso.";
} else {
    $_SESSION['erro'] = "Erro ao atualizar comentário.";
}

header('Location: comentarios.php');
exit;
