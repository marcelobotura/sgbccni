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

try {
  if ($acao === 'remover') {
    // Remover completamente o relacionamento (ex: excluir da tabela)
    $stmt = $conn->prepare("DELETE FROM livros_usuarios WHERE usuario_id = ? AND livro_id = ?");
    $stmt->execute([$usuario_id, $livro_id]);

  } elseif ($acao === 'favorito') {
    // Marca como favorito (ou insere se não existir)
    $stmt = $conn->prepare("INSERT INTO livros_usuarios (usuario_id, livro_id, favorito) 
                            VALUES (?, ?, 1)
                            ON DUPLICATE KEY UPDATE favorito = 1");
    $stmt->execute([$usuario_id, $livro_id]);

  } elseif ($acao === 'lido') {
    // Marca como lido (ou atualiza a leitura se já existir)
    $stmt = $conn->prepare("INSERT INTO livros_usuarios (usuario_id, livro_id, status, data_leitura)
                            VALUES (?, ?, 'lido', NOW())
                            ON DUPLICATE KEY UPDATE status = 'lido', data_leitura = NOW()");
    $stmt->execute([$usuario_id, $livro_id]);
  }

  $_SESSION['sucesso'] = "Ação realizada com sucesso.";
} catch (PDOException $e) {
  $_SESSION['erro'] = "Erro ao executar ação: " . $e->getMessage();
}

// Redireciona de forma inteligente
$origem = $_POST['origem'] ?? 'historico';
header("Location: {$origem}.php");
exit;
