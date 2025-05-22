<?php
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}

define('NOME_SISTEMA', 'Biblioteca CNI');

$usuario_logado = isset($_SESSION['usuario_id']);
$nome_usuario = $_SESSION['usuario_nome'] ?? 'Visitante';
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= htmlspecialchars(NOME_SISTEMA) ?></title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark px-4">
  <a class="navbar-brand fw-bold" href="<?= URL_BASE ?>">📚 <?= htmlspecialchars(NOME_SISTEMA) ?></a>

  <div class="collapse navbar-collapse justify-content-end">
    <ul class="navbar-nav">
      <?php if ($usuario_logado): ?>
        <?php if ($_SESSION['usuario_tipo'] === 'admin'): ?>
          <li class="nav-item"><a class="nav-link" href="<?= URL_BASE ?>admin/listar_livros.php">Painel Admin</a></li>
        <?php else: ?>
          <li class="nav-item"><a class="nav-link" href="<?= URL_BASE ?>usuario/index.php">Minha Área</a></li>
          <li class="nav-item"><a class="nav-link" href="<?= URL_BASE ?>usuario/perfil.php">Meu Perfil</a></li>
        <?php endif; ?>
        <li class="nav-item"><a class="nav-link text-danger" href="<?= URL_BASE ?>login/logout.php">Sair</a></li>
        <li class="nav-item"><span class="nav-link disabled">👤 <?= htmlspecialchars($nome_usuario) ?></span></li>
      <?php else: ?>
        <li class="nav-item"><a class="nav-link" href="<?= URL_BASE ?>login/login.php">Entrar</a></li>
        <li class="nav-item"><a class="nav-link" href="<?= URL_BASE ?>login/register.php">Cadastrar</a></li>
        <li class="nav-item"><span class="nav-link disabled">👤 Visitante</span></li>
      <?php endif; ?>
    </ul>
  </div>
</nav>
