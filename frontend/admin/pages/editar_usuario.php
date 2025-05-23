<?php
session_start();
define('BASE_PATH', dirname(__DIR__) . '/backend');
require_once BASE_PATH . '/config/config.php';
require_once BASE_PATH . '/includes/session.php';
require_once BASE_PATH . '/includes/header.php';

exigir_login('admin');

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
  $_SESSION['erro'] = "ID inválido.";
  header("Location: usuarios.php");
  exit;
}

$id = intval($_GET['id']);

// PROCESSAMENTO DO FORMULÁRIO
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $nome  = trim($_POST['nome'] ?? '');
  $email = trim($_POST['email'] ?? '');
  $tipo  = $_POST['tipo'] ?? 'usuario';
  $nova_senha = $_POST['senha'] ?? '';

  if (empty($nome) || empty($email) || !in_array($tipo, ['admin', 'usuario'])) {
    $_SESSION['erro'] = "Preencha os dados corretamente.";
    header("Location: editar_usuario.php?id=$id");
    exit;
  }

  // Atualização com ou sem nova senha
  if (!empty($nova_senha)) {
    $senha_hash = password_hash($nova_senha, PASSWORD_DEFAULT);
    $sql = "UPDATE usuarios SET nome = ?, email = ?, tipo = ?, senha = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssi", $nome, $email, $tipo, $senha_hash, $id);
  } else {
    $sql = "UPDATE usuarios SET nome = ?, email = ?, tipo = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssi", $nome, $email, $tipo, $id);
  }

  if ($stmt->execute()) {
    $_SESSION['sucesso'] = "Usuário atualizado com sucesso.";
  } else {
    $_SESSION['erro'] = "Erro ao atualizar usuário.";
  }

  $stmt->close();
  header("Location: usuarios.php");
  exit;
}

// CONSULTA PARA EXIBIÇÃO DO FORMULÁRIO
$stmt = $conn->prepare("SELECT nome, email, tipo FROM usuarios WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$stmt->bind_result($nome, $email, $tipo);
$stmt->fetch();
$stmt->close();
?>

<div class="container py-4">
  <h3 class="mb-4">✏️ Editar Usuário</h3>

  <form method="POST">
    <div class="mb-3">
      <label for="nome" class="form-label">Nome:</label>
      <input type="text" name="nome" id="nome" class="form-control" value="<?= htmlspecialchars($nome) ?>" required>
    </div>

    <div class="mb-3">
      <label for="email" class="form-label">E-mail:</label>
      <input type="email" name="email" id="email" class="form-control" value="<?= htmlspecialchars($email) ?>" required>
    </div>

    <div class="mb-3">
      <label for="tipo" class="form-label">Tipo de usuário:</label>
      <select name="tipo" id="tipo" class="form-select" required>
        <option value="usuario" <?= $tipo === 'usuario' ? 'selected' : '' ?>>Usuário</option>
        <option value="admin" <?= $tipo === 'admin' ? 'selected' : '' ?>>Administrador</option>
      </select>
    </div>

    <div class="mb-3">
      <label for="senha" class="form-label">Nova senha (opcional):</label>
      <input type="password" name="senha" id="senha" class="form-control">
      <div class="form-text">Deixe em branco para manter a senha atual.</div>
    </div>

    <button type="submit" class="btn btn-primary">Salvar Alterações</button>
    <a href="usuarios.php" class="btn btn-secondary">Cancelar</a>
  </form>
</div>

<?php require_once BASE_PATH . '/includes/footer.php'; ?>
