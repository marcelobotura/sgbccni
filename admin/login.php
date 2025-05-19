<?php
session_start();
$erro = '';
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $usuario = $_POST['usuario'];
    $senha = $_POST['senha'];
    if ($usuario === 'admin' && $senha === '1234') {
        $_SESSION['admin_logado'] = true;
        header("Location: pages/cadastrar_livro.php");
        exit;
    } else {
        $erro = "Usuário ou senha inválidos!";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <title>Login Admin - Biblioteca CNI</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="../assets/css/style.css" rel="stylesheet">
  <style>
    body {
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
      margin: 0;
    }
    .login-box {
      width: 100%;
      max-width: 400px;
      padding: 30px;
      background-color: var(--card-color);
      color: var(--text-color);
      border: 1px solid var(--border-color);
      border-radius: 8px;
    }
  </style>
</head>
<body class="<?= ($_COOKIE['modo_tema'] ?? 'dark') === 'light' ? 'light-mode' : '' ?>">

  <div class="login-box">
    <h3 class="mb-4 text-center">🔐 Login Administrativo</h3>
    <form method="POST">
      <div class="mb-3">
        <input type="text" name="usuario" class="form-control" placeholder="Usuário" required>
      </div>
      <div class="mb-3">
        <input type="password" name="senha" class="form-control" placeholder="Senha" required>
      </div>
      <?php if ($erro): ?>
        <div class="alert alert-danger"><?= $erro ?></div>
      <?php endif; ?>
      <button type="submit" class="btn btn-primary w-100">Entrar</button>
    </form>
  </div>

</body>
</html>
