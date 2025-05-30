<?php
require_once __DIR__ . '/../backend/config/config.php';
require_once __DIR__ . '/../backend/includes/session.php';

if (isset($_SESSION['usuario_tipo'])) {
    $redirect = $_SESSION['usuario_tipo'] === 'admin'
        ? URL_BASE . 'frontend/admin/pages/index.php'
        : URL_BASE . 'frontend/usuario/index.php';

    header("Location: $redirect");
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <title>Bem-vindo à Biblioteca CNI</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
  <div class="container text-center py-5">
    <h1 class="mb-4">📚 Biblioteca Comunitária CNI</h1>
    <p class="lead mb-4">Sistema de leitura, empréstimos e comunidade.</p>
    <a href="login.php" class="btn btn-primary btn-lg me-2">Entrar</a>
    <a href="frontend/login/register.php" class="btn btn-outline-success btn-lg">Criar Conta</a>
  </div>
</body>
</html>
