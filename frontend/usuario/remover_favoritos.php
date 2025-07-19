<?php
require_once __DIR__ . '/../../backend/config/config.php';
require_once __DIR__ . '/../../backend/includes/session.php';
require_once __DIR__ . '/protect_usuario.php';

exigir_login('usuario');

$usuario_id = $_SESSION['usuario_id'];
$livro_id = intval($_GET['id'] ?? 0);

if ($livro_id > 0) {
    $sql = "UPDATE livros_usuarios SET favorito = 0 WHERE usuario_id = ? AND livro_id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$usuario_id, $livro_id]);
}

header("Location: favoritos.php");
exit;
