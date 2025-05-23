<?php
require_once __DIR__ . '/../../backend/config/config.php';
require_once __DIR__ . '/../../backend/includes/session.php';
require_once __DIR__ . '/../../backend/includes/header.php';
require_once __DIR__ . '/../../backend/includes/menu.php';

exigir_login('usuario');
?>

<div class="container py-4">
  <div class="text-center">
    <h2 class="fw-bold">👋 Olá, <?= htmlspecialchars($_SESSION['usuario_nome']) ?>!</h2>
    <p class="text-muted">Bem-vindo à sua área de leitura da <strong>Biblioteca CNI</strong>.</p>
  </div>

  <div class="row mt-4 g-3">
    <div class="col-md-6">
      <div class="card border-info shadow-sm">
        <div class="card-body">
          <h5 class="card-title">📖 Meus Livros</h5>
          <p class="card-text">Veja os livros que você já leu ou está lendo.</p>
          <a href="meus_livros.php" class="btn btn-outline-info btn-sm">Acessar</a>
        </div>
      </div>
    </div>

    <div class="col-md-6">
      <div class="card border-secondary shadow-sm">
        <div class="card-body">
          <h5 class="card-title">⭐ Favoritos</h5>
          <p class="card-text">Visualize sua lista de livros favoritos.</p>
          <a href="favoritos.php" class="btn btn-outline-secondary btn-sm">Ver Favoritos</a>
        </div>
      </div>
    </div>
  </div>
</div>

<?php require_once __DIR__ . '/../../backend/includes/footer.php'; ?>
