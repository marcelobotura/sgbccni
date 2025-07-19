<?php
define('BASE_PATH', dirname(__DIR__, 2));
require_once BASE_PATH . '/backend/config/config.php';
require_once BASE_PATH . '/backend/includes/session.php';

exigir_login('usuario');

$usuario_id = $_SESSION['usuario_id'];
$livro_id = intval($_GET['livro_id'] ?? 0);
$acao = $_GET['acao'] ?? '';

if ($livro_id > 0) {
    if ($acao === 'adicionar') {
        $stmt = $pdo->prepare("INSERT INTO livros_usuarios (usuario_id, livro_id, favorito)
                               VALUES (?, ?, 1)
                               ON DUPLICATE KEY UPDATE favorito = 1");
        $stmt->execute([$usuario_id, $livro_id]);
        $_SESSION['sucesso'] = "Livro adicionado aos favoritos!";
    } elseif ($acao === 'remover') {
        $stmt = $pdo->prepare("UPDATE livros_usuarios SET favorito = 0 WHERE usuario_id = ? AND livro_id = ?");
        $stmt->execute([$usuario_id, $livro_id]);
        $_SESSION['sucesso'] = "Livro removido dos favoritos.";
    }
}

// Redireciona de volta para os detalhes do livro
header("Location: livro.php?id=$livro_id");
exit;
