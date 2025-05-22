<?php
define('BASE_PATH', dirname(__DIR__) . '/../backend'); // Caminho atualizado
require_once BASE_PATH . '/config/config.php';
require_once BASE_PATH . '/includes/session.php';
session_start();

if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_tipo'] !== 'admin') {
    header('Location: ../login/index.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <title>Painel Administrativo - Biblioteca CNI</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
  <style>
    body {
      background-color: #f8f9fa;
    }
    .admin-box {
      margin-top: 50px;
    }
  </style>
</head>
<body>
  <div class="container admin-box">
    <div class="d-flex justify-content-between align-items-center mb-4">
      <div>
        <h3 class="fw-bold">📊 Painel Administrativo</h3>
        <p class="text-muted">Bem-vindo, <?= htmlspecialchars($_SESSION['usuario_nome'] ?? 'Administrador') ?>!</p>
      </div>
      <a href="../login/logout.php" class="btn btn-outline-danger">
        <i class="bi bi-box-arrow-right"></i> Sair
      </a>
    </div>

    <div class="row g-3">
      <div class="col-md-4">
        <a href="cadastrar_livro.php" class="btn btn-primary w-100">
          <i class="bi bi-plus-circle"></i> Cadastrar Livro
        </a>
      </div>
      <div class="col-md-4">
        <a href="listar_livros.php" class="btn btn-secondary w-100">
          <i class="bi bi-journal-text"></i> Listar Livros
        </a>
      </div>
      <div class="col-md-4">
        <a href="usuarios.php" class="btn btn-dark w-100">
          <i class="bi bi-people-fill"></i> Gerenciar Usuários
        </a>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
