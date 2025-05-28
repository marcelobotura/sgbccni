<?php
session_start();
require_once __DIR__ . '/../../backend/config/config.php';
require_once __DIR__ . '/../../backend/includes/session.php';

exigir_login('usuario');

$usuario_id = $_SESSION['usuario_id'];
$livro_id = intval($_POST['livro_id'] ?? 0);

if ($livro_id > 0) {
    $stmt = $conn->prepare("UPDATE livros_usuarios SET status = 'lido', data_leitura = NOW() WHERE usuario_id = ? AND livro_id = ?");
    $stmt->bind_param("ii", $usuario_id, $livro_id);
    $stmt->execute();
    $stmt->close();
}

header("Location: historico.php");
exit;
