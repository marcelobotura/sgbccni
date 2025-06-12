<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../includes/session.php';

exigir_login('usuario');

$usuario_id = $_SESSION['usuario_id'];
$livro_id = intval($_GET['livro_id'] ?? 0);
$acao = $_GET['acao'] ?? '';

if ($livro_id > 0) {
    if ($acao === 'adicionar') {
        $stmt = $conn->prepare("INSERT INTO livros_usuarios (usuario_id, livro_id, favorito)
                                VALUES (?, ?, 1)
                                ON DUPLICATE KEY UPDATE favorito = 1");
        $stmt->execute([$usuario_id, $livro_id]);
    } elseif ($acao === 'remover') {
        $stmt = $conn->prepare("UPDATE livros_usuarios SET favorito = 0 WHERE usuario_id = ? AND livro_id = ?");
        $stmt->execute([$usuario_id, $livro_id]);
    }
}

header("Location: detalhes.php?id=$livro_id");
exit;
