<?php
require_once __DIR__ . '/../includes/protect.php';
require_once __DIR__ . '/../includes/header.php';
?>

<div class="container py-4">
  <h2 class="mb-4 text-center">
    <i class="bi bi-speedometer2"></i> Painel Administrativo
  </h2>

  <div class="row g-4">
    <!-- Cadastrar Livro -->
    <div class="col-md-6 col-lg-4">
      <div class="card shadow-sm border-0 h-100">
        <div class="card-body text-center">
          <i class="bi bi-plus-circle display-4 text-primary"></i>
          <h5 class="card-title mt-3">Cadastrar Livro</h5>
          <p class="text-card">Adicione novos livros ao sistema.</p>
          <a href="pages/cadastrar_livro.php" class="btn btn-outline-primary w-100">Acessar</a>
        </div>
      </div>
    </div>

    <!-- Gerenciar Tags -->
    <div class="col-md-6 col-lg-4">
      <div class="card shadow-sm border-0 h-100">
        <div class="card-body text-center">
          <i class="bi bi-tags display-4 text-success"></i>
          <h5 class="card-title mt-3">Gerenciar Tags</h5>
          <p class="text-card">Autores, categorias e editoras.</p>
          <a href="pages/gerenciar_tags.php" class="btn btn-outline-success w-100">Acessar</a>
        </div>
      </div>
    </div>

    <!-- Sair -->
    <div class="col-md-6 col-lg-4">
      <div class="card shadow-sm border-0 h-100">
        <div class="card-body text-center">
          <i class="bi bi-box-arrow-right display-4 text-danger"></i>
          <h5 class="card-title mt-3">Sair</h5>
          <p class="text-card">Encerrar a sessão administrativa.</p>
          <a href="logout.php" class="btn btn-outline-danger w-100">Desconectar</a>
        </div>
      </div>
    </div>
  </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
