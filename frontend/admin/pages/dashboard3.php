<?php
// Caminho: frontend/admin/pages/dashboard.php
require_once __DIR__ . '/../../../backend/includes/session.php';
?>

<div class="container-fluid py-4">
  <div class="row mb-4 text-center">
    <div class="col">
      <h1 class="display-6 fw-bold text-primary mb-2">
        <i class="bi bi-speedometer2 me-2"></i>Painel Administrativo
      </h1>
      <p class="text-muted">Bem-vindo(a), <?= htmlspecialchars($_SESSION['usuario_nome'] ?? 'Administrador') ?>!</p>
    </div>
  </div>

  <div class="row g-4">
    <div class="col-md-4">
      <a href="pages/gerenciar_usuarios.php" data-page="gerenciar_usuarios" class="card card-hover h-100 text-decoration-none text-dark shadow-sm">
        <div class="card-body text-center">
          <i class="bi bi-people-fill display-4 text-primary mb-3"></i>
          <h5 class="card-title fw-semibold">Usuários</h5>
          <p class="card-text">Gerencie usuários, permissões e cadastros.</p>
        </div>
      </a>
    </div>

    <div class="col-md-4">
      <a href="pages/gerenciar_livros.php" data-page="gerenciar_livros" class="card card-hover h-100 text-decoration-none text-dark shadow-sm">
        <div class="card-body text-center">
          <i class="bi bi-journal-bookmark display-4 text-success mb-3"></i>
          <h5 class="card-title fw-semibold">Livros</h5>
          <p class="card-text">Adicione, edite ou exclua livros da biblioteca.</p>
        </div>
      </a>
    </div>

    <div class="col-md-4">
      <a href="pages/gerenciar_mensagens.php" data-page="gerenciar_mensagens" class="card card-hover h-100 text-decoration-none text-dark shadow-sm">
        <div class="card-body text-center">
          <i class="bi bi-envelope-paper-fill display-4 text-warning mb-3"></i>
          <h5 class="card-title fw-semibold">Mensagens</h5>
          <p class="card-text">Leia mensagens enviadas pelo público.</p>
        </div>
      </a>
    </div>

    <div class="col-md-4">
      <a href="pages/gerenciar_comentarios.php" data-page="gerenciar_comentarios" class="card card-hover h-100 text-decoration-none text-dark shadow-sm">
        <div class="card-body text-center">
          <i class="bi bi-chat-dots display-4 text-info mb-3"></i>
          <h5 class="card-title fw-semibold">Comentários</h5>
          <p class="card-text">Modere comentários nos livros do sistema.</p>
        </div>
      </a>
    </div>

    <div class="col-md-4">
      <a href="pages/gerenciar_relatorios" data-page="gerenciar_relatorios" class="card card-hover h-100 text-decoration-none text-dark shadow-sm">
        <div class="card-body text-center">
          <i class="bi bi-bar-chart-fill display-4 text-danger mb-3"></i>
          <h5 class="card-title fw-semibold">Relatórios</h5>
          <p class="card-text">Consulte estatísticas e dados de uso.</p>
        </div>
      </a>
    </div>

    <div class="col-md-4">
      <a href="#" data-page="backup" class="card card-hover h-100 text-decoration-none text-dark shadow-sm">
        <div class="card-body text-center">
          <i class="bi bi-cloud-arrow-down-fill display-4 text-secondary mb-3"></i>
          <h5 class="card-title fw-semibold">Backup</h5>
          <p class="card-text">Crie cópias de segurança do sistema.</p>
        </div>
      </a>
    </div>
  </div>
</div>

<style>
  .card-hover:hover {
    background-color: #f0f8ff;
    transform: translateY(-2px);
    transition: all 0.2s ease-in-out;
  }
</style>
