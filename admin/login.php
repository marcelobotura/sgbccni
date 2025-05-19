<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$erro = '';

// Se for POST, processa o login antes de qualquer HTML
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $usuario = $_POST['usuario'];
    $senha = $_POST['senha'];

    if ($usuario === 'admin' && $senha === '1234') {
        $_SESSION['admin_logado'] = true;
        header("Location: index.php");
        exit;
    } else {
        $erro = "Usuário ou senha inválidos!";
    }
}

// Inclui header somente se for GET ou erro após tentativa
require_once __DIR__ . '/../includes/header.php';
?>

<div class="container d-flex justify-content-center align-items-center" style="min-height: 80vh;">
  <div class="login-box p-4 border rounded shadow-sm" style="max-width: 400px; width: 100%; background-color: var(--card-color, #2c2c2c); color: var(--text-color, #fff);">
    <h3 class="mb-4 text-center">🔐 Login Administrativo</h3>
    <form method="POST">
      <div class="mb-3">
        <input type="text" name="usuario" class="form-control" placeholder="Usuário" required>
      </div>
      <div class="mb-3">
        <input type="password" name="senha" class="form-control" placeholder="Senha" required>
      </div>
      <?php if (!empty($erro)): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($erro) ?></div>
      <?php endif; ?>
      <button type="submit" class="btn btn-primary w-100">Entrar</button>
    </form>
  </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
