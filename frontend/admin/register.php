<?php
session_start();
require_once __DIR__ . '/../../backend/includes/header.php';
require_once __DIR__ . '/../../backend/includes/menu.php';
require_once __DIR__ . '/../../backend/config/config.php';
exigir_login('admin');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $nome  = trim($_POST['nome'] ?? '');
  $email = trim($_POST['email'] ?? '');
  $senha = $_POST['senha'] ?? '';

  if ($nome === '' || $email === '' || $senha === '') {
    $_SESSION['erro'] = 'Preencha todos os campos obrigatórios.';
  } else {
    // Verifica se e-mail já existe
    $stmt = $conn->prepare("SELECT id FROM usuarios WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
      $_SESSION['erro'] = 'Este e-mail já está em uso.';
    } else {
      $senha_hash = password_hash($senha, PASSWORD_DEFAULT);
      $stmt = $conn->prepare("INSERT INTO usuarios (nome, email, senha, tipo) VALUES (?, ?, ?, 'admin')");
      $stmt->bind_param("sss", $nome, $email, $senha_hash);

      if ($stmt->execute()) {
        $_SESSION['sucesso'] = 'Administrador cadastrado com sucesso!';
        header("Location: cadastrar_admin.php");
        exit;
      } else {
        $_SESSION['erro'] = 'Erro ao cadastrar administrador.';
      }
    }
    $stmt->close();
  }
}
?>

<div class="container py-4">
  <h2 class="mb-4">👤 Cadastrar Novo Administrador</h2>

  <?php if (!empty($_SESSION['erro'])): ?>
    <div class="alert alert-danger"><?= htmlspecialchars($_SESSION['erro']); unset($_SESSION['erro']); ?></div>
  <?php elseif (!empty($_SESSION['sucesso'])): ?>
    <div class="alert alert-success"><?= htmlspecialchars($_SESSION['sucesso']); unset($_SESSION['sucesso']); ?></div>
  <?php endif; ?>

  <form method="POST" action="">
    <div class="mb-3">
      <label for="nome" class="form-label">Nome *</label>
      <input type="text" name="nome" id="nome" class="form-control" required>
    </div>

    <div class="mb-3">
      <label for="email" class="form-label">E-mail *</label>
      <input type="email" name="email" id="email" class="form-control" required>
    </div>

    <div class="mb-3">
      <label for="senha" class="form-label">Senha *</label>
      <input type="password" name="senha" id="senha" class="form-control" required>
    </div>

    <button type="submit" class="btn btn-success">Cadastrar Administrador</button>
  </form>
</div>

<?php require_once __DIR__ . '/../../backend/includes/footer.php'; ?>
