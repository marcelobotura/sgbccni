<?php
session_start();
define('BASE_PATH', dirname(__DIR__) . '/backend');
require_once BASE_PATH . '/config/config.php';
require_once BASE_PATH . '/includes/session.php';
require_once BASE_PATH . '/includes/header.php';

exigir_login('admin');

// Consultas simples
$qtdUsuarios = $conn->query("SELECT COUNT(*) as total FROM usuarios WHERE tipo = 'usuario'")->fetch_assoc()['total'];
$qtdAdmins   = $conn->query("SELECT COUNT(*) as total FROM usuarios WHERE tipo = 'admin'")->fetch_assoc()['total'];
$qtdLivros   = $conn->query("SELECT COUNT(*) as total FROM livros")->fetch_assoc()['total'];
$qtdFavoritos = $conn->query("SELECT COUNT(*) as total FROM livros_usuarios WHERE status = 'favorito'")->fetch_assoc()['total'];
?>

<div class="container py-4">
  <h3 class="mb-4">📋 Painel Administrativo</h3>

  <div class="row row-cols-1 row-cols-md-4 g-4">

    <div class="col">
      <div class="card shadow-sm border-0 text-center">
        <div class="card-body">
          <h1 class="text-primary fw-bold"><?= $qtdUsuarios ?></h1>
          <p class="mb-0">Usuários</p>
        </div>
      </div>
    </div>

    <div class="col">
      <div class="card shadow-sm border-0 text-center">
        <div class="card-body">
          <h1 class="text-dark fw-bold"><?= $qtdAdmins ?></h1>
          <p class="mb-0">Administradores</p>
        </div>
      </div>
    </div>

    <div class="col">
      <div class="card shadow-sm border-0 text-center">
        <div class="card-body">
          <h1 class="text-success fw-bold"><?= $qtdLivros ?></h1>
          <p class="mb-0">Livros cadastrados</p>
        </div>
      </div>
    </div>

    <div class="col">
      <div class="card shadow-sm border-0 text-center">
        <div class="card-body">
          <h1 class="text-warning fw-bold"><?= $qtdFavoritos ?></h1>
          <p class="mb-0">Livros favoritos</p>
        </div>
      </div>
    </div>

  </div>

  <div class="mt-5">
    <a href="usuarios.php" class="btn btn-outline-secondary me-2">👥 Gerenciar Usuários</a>
    <a href="pages/cadastrar_livro.php" class="btn btn-outline-success me-2">➕ Novo Livro</a>
    <a href="pages/listar_livros.php" class="btn btn-outline-primary">📚 Gerenciar Livros</a>
  </div>
</div>

<?php require_once BASE_PATH . '/includes/footer.php'; ?>
