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
    <div class="col-md-6 col-lg-4">
      <div class="card border-info shadow-sm h-100">
        <div class="card-body d-flex flex-column">
          <h5 class="card-title">📚 Meus Livros</h5>
          <p class="card-text">Veja os livros que você já leu ou está lendo.</p>
          <a href="meus_livros.php" class="btn btn-outline-info mt-auto">
            <i class="bi bi-journal-check"></i> Acessar
          </a>
        </div>
      </div>
    </div>

    <div class="col-md-6 col-lg-4">
      <div class="card border-secondary shadow-sm h-100">
        <div class="card-body d-flex flex-column">
          <h5 class="card-title">⭐ Favoritos</h5>
          <p class="card-text">Visualize sua lista de livros favoritos.</p>
          <a href="favoritos.php" class="btn btn-outline-secondary mt-auto">
            <i class="bi bi-star"></i> Ver Favoritos
          </a>
        </div>
      </div>
    </div>

    <!-- Exemplo de card futuro: Histórico -->
    <div class="col-md-6 col-lg-4">
      <div class="card border-success shadow-sm h-100">
        <div class="card-body d-flex flex-column">
          <h5 class="card-title">🕓 Histórico</h5>
          <p class="card-text">Veja todos os livros que você já registrou como lidos.</p>
          <a href="historico.php" class="btn btn-outline-success mt-auto">
            <i class="bi bi-clock-history"></i> Ver Histórico
          </a>
        </div>
      </div>
    </div>

  </div>
</div>

<?php require_once __DIR__ . '/../../backend/includes/footer.php'; ?>
