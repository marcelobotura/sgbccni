<?php
require_once __DIR__ . '/../../backend/config/config.php';
require_once __DIR__ . '/../../backend/includes/session.php';
exigir_login('usuario');

$usuario_id = $_SESSION['usuario_id'];
$livro_id = intval($_POST['livro_id'] ?? 0);
$acao = $_POST['acao'] ?? '';

if ($livro_id <= 0 || !in_array($acao, ['lido', 'favorito', 'remover'])) {
  header("Location: historico.php");
  exit;
}

switch ($acao) {
  case 'remover':
    $stmt = $conn->prepare("DELETE FROM livros_usuarios WHERE usuario_id = ? AND livro_id = ?");
    $stmt->bind_param("ii", $usuario_id, $livro_id);
    break;
  default:
    $stmt = $conn->prepare("UPDATE livros_usuarios SET status = ?, data_leitura = NOW() WHERE usuario_id = ? AND livro_id = ?");
    $stmt->bind_param("sii", $acao, $usuario_id, $livro_id);
    break;
}

$stmt->execute();
$stmt->close();
header("Location: historico.php");
exit;
