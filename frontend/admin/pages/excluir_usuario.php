// Conteúdo do frontend/admin/pages/excluir_usuario.php com as melhorias sugeridas:
<?php
session_start();
define('BASE_PATH', dirname(__DIR__) . '/backend'); // Ajuste conforme a sua estrutura de pastas
require_once BASE_PATH . '/config/config.php';
require_once BASE_PATH . '/includes/session.php';

exigir_login('admin');

// 🧪 Verifica se o ID foi enviado corretamente
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
  $_SESSION['erro'] = "ID inválido.";
  header("Location: usuarios.php");
  exit;
}

$id = intval($_GET['id']);

// 🚫 Impede o administrador de excluir sua própria conta
if ($_SESSION['usuario_id'] == $id) {
  $_SESSION['erro'] = "Você não pode excluir sua própria conta.";
  header("Location: usuarios.php");
  exit;
}

// 🔎 Verifica se o usuário existe
$stmt = $conn->prepare("SELECT id FROM usuarios WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows === 0) {
  $_SESSION['erro'] = "Usuário não encontrado.";
  $stmt->close();
  header("Location: usuarios.php");
  exit;
}
$stmt->close();

// ✅ Inicia a transação
$conn->begin_transaction();

try {
  // ❌ Exclui registros relacionados ANTES de excluir o usuário principal
  // ISSO É CRÍTICO SE VOCÊ NÃO TIVER 'ON DELETE CASCADE' CONFIGURADO NO BANCO DE DADOS
  // Se você tiver ON DELETE CASCADE, pode pular estas exclusões explícitas.

  // Exclui de livros_usuarios
  $stmt_livros = $conn->prepare("DELETE FROM livros_usuarios WHERE usuario_id = ?");
  $stmt_livros->bind_param("i", $id);
  $stmt_livros->execute();
  $stmt_livros->close();

  // Exclui de tokens_recuperacao
  $stmt_tokens = $conn->prepare("DELETE FROM tokens_recuperacao WHERE usuario_id = ?");
  $stmt_tokens->bind_param("i", $id);
  $stmt_tokens->execute();
  $stmt_tokens->close();

  // ❌ Exclui o usuário da tabela 'usuarios'
  $stmt_usuario = $conn->prepare("DELETE FROM usuarios WHERE id = ?");
  $stmt_usuario->bind_param("i", $id);

  if ($stmt_usuario->execute()) {
    $stmt_usuario->close();
    $conn->commit(); // Confirma a transação
    $_SESSION['sucesso'] = "Usuário excluído com sucesso!";
  } else {
    throw new Exception("Erro ao excluir usuário: " . $stmt_usuario->error);
  }
} catch (Exception $e) {
  $conn->rollback(); // Desfaz a transação em caso de erro
  $_SESSION['erro'] = "Erro ao excluir usuário: " . $e->getMessage();
}

header("Location: usuarios.php");
exit;
?>