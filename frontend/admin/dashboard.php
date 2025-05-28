<?php
session_start();
define('BASE_PATH', dirname(__DIR__) . '/backend');
require_once BASE_PATH . '/config/config.php';
require_once BASE_PATH . '/includes/session.php';
require_once BASE_PATH . '/includes/header.php';

exigir_login('admin');

// 🔢 Coleta estatísticas básicas
$total_usuarios = $conn->query("SELECT COUNT(*) FROM usuarios")->fetch_row()[0];
$total_livros   = $conn->query("SELECT COUNT(*) FROM livros")->fetch_row()[0];
$total_msgs     = $conn->query("SELECT COUNT(*) FROM mensagens_contato")->fetch_row()[0];
$total_leituras = $conn->query("SELECT COUNT(*) FROM livros_usuarios")->fetch_row()[0];
?>

<div class="container py-4">
  <h2 class="mb-4">📊 Painel Administrativo</h2>

  <div class="row g-4">
    <div class="col-md-3">
      <div class="card text-white bg-primary shadow-sm">
        <div class="card-body">
          <h5 class="card-title">Usuários</h5>
          <p class="card-text fs-3"><?= $total_usuarios ?></p>
        </div>
        <div class="card-footer bg-transparent border-top-0">
          <a href="usuarios.php" class="text-white text-decoration-none">Gerenciar <i class="bi bi-chevron-right"></i></a>
        </div>
      </div>
    </div>

    <div class="col-md-3">
      <div class="card text-white bg-success shadow-sm">
        <div class="card-body">
          <h5 class="card-title">Livros</h5>
          <p class="card-text fs-3"><?= $total_livros ?></p>
        </div>
        <div class="card-footer bg-transparent border-top-0">
          <a href="listar_livros.php" class="text-white text-decoration-none">Ver todos <i class="bi bi-chevron-right"></i></a>
        </div>
      </div>
    </div>

    <div class="col-md-3">
      <div class="card text-white bg-warning shadow-sm">
        <div class="card-body">
          <h5 class="card-title">Leituras</h5>
          <p class="card-text fs-3"><?= $total_leituras ?></p>
        </div>
        <div class="card-footer bg-transparent border-top-0">
          <a href="listar_leituras.php" class="text-white text-decoration-none">Acompanhar <i class="bi bi-chevron-right"></i></a>
        </div>
      </div>
    </div>

    <div class="col-md-3">
      <div class="card text-white bg-danger shadow-sm">
        <div class="card-body">
          <h5 class="card-title">Mensagens</h5>
          <p class="card-text fs-3"><?= $total_msgs ?></p>
        </div>
        <div class="card-footer bg-transparent border-top-0">
          <a href="mensagens.php" class="text-white text-decoration-none">Ver mensagens <i class="bi bi-chevron-right"></i></a>
        </div>
      </div>
    </div>
  </div>

  <hr class="my-5">

  <div class="text-center">
    <a href="<?= URL_BASE ?>" class="btn btn-outline-secondary">🔙 Voltar ao site</a>
  </div>
</div>

<?php require_once BASE_PATH . '/includes/footer.php'; ?>
