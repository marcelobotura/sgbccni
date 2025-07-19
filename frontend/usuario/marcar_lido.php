<?php
// Caminho: frontend/usuario/marcar_lido.php

define('BASE_PATH', dirname(__DIR__, 2));
require_once BASE_PATH . '/backend/config/config.php';
require_once BASE_PATH . '/backend/includes/session.php';

exigir_login('usuario');

$usuario_id = $_SESSION['usuario_id'] ?? null;
$livro_id = isset($_POST['livro_id']) ? intval($_POST['livro_id']) : null;

if (!$usuario_id || !$livro_id) {
    $_SESSION['erro'] = "Código ou ISBN não informado.";
    header("Location: index.php");
    exit;
}

try {
    $stmt = $pdo->prepare("SELECT * FROM livros_usuarios WHERE usuario_id = ? AND livro_id = ?");
    $stmt->execute([$usuario_id, $livro_id]);
    $existe = $stmt->fetch();

    if ($existe) {
        $stmt = $pdo->prepare("UPDATE livros_usuarios 
                               SET status = 'lido', data_leitura = NOW()
                               WHERE usuario_id = :uid AND livro_id = :lid");
    } else {
        $stmt = $pdo->prepare("INSERT INTO livros_usuarios (usuario_id, livro_id, status, data_leitura) 
                               VALUES (:uid, :lid, 'lido', NOW())");
    }

    $stmt->execute([
        ':uid' => $usuario_id,
        ':lid' => $livro_id
    ]);

    $_SESSION['sucesso'] = "✅ Livro marcado como lido!";
    header("Location: livro.php?id=" . $livro_id);
    exit;

} catch (PDOException $e) {
    $_SESSION['erro'] = "Erro ao marcar como lido: " . $e->getMessage();
    header("Location: livro.php?id=" . $livro_id);
    exit;
}
