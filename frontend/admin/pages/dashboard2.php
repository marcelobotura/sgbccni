<?php
// Caminho: frontend/admin/pages/dashboard.php

require_once __DIR__ . '/../../../backend/includes/protect_admin.php';
require_once __DIR__ . '/../../../backend/config/config.php';
require_once __DIR__ . '/../../../backend/includes/db.php';

// Estatísticas simples
$totalUsuarios = $pdo->query("SELECT COUNT(*) FROM usuarios")->fetchColumn();
$totalLivros = $pdo->query("SELECT COUNT(*) FROM livros")->fetchColumn();
$totalSugestoesPendentes = $pdo->query("SELECT COUNT(*) FROM sugestoes WHERE resposta IS NULL")->fetchColumn();
$totalComentariosPendentes = $pdo->query("SELECT COUNT(*) FROM comentarios WHERE aprovado = 0")->fetchColumn();
?>

<div class="container-fluid">
  <div class="row mb-4">
    <div class="col">
      <h3 class="mb-0"><i class="bi bi-speedometer2"></i> Visão Geral do Sistema</h3>
      <p class="text-muted">Resumo rápido das principais informações</p>
    </div>
  </div>

  <div class="row g-4">
    <div class="col-md-3">
      <div class="card border-0 shadow-sm">
        <div class="card-body">
          <h5 class="card-title text-primary"><i class="bi bi-people"></i> Usuários</h5>
          <p class="card-text display-6 fw-bold"><?= $totalUsuarios ?></p>
          <a href="#" data-page="gerenciar_usuarios" class="stretched-link">Ver todos</a>
        </div>
      </div>
    </div>
    <div class="col-md-3">
      <div class="card border-0 shadow-sm">
        <div class="card-body">
          <h5 class="card-title text-success"><i class="bi bi-journal-text"></i> Livros</h5>
          <p class="card-text display-6 fw-bold"><?= $totalLivros ?></p>
          <a href="#" data-page="gerenciar_livros" class="stretched-link">Ver acervo</a>
        </div>
      </div>
    </div>
    <div class="col-md-3">
      <div class="card border-0 shadow-sm">
        <div class="card-body">
          <h5 class="card-title text-warning"><i class="bi bi-lightbulb"></i> Sugestões</h5>
          <p class="card-text display-6 fw-bold"><?= $totalSugestoesPendentes ?></p>
          <a href="#" data-page="gerenciar_sugestoes" class="stretched-link">Analisar</a>
        </div>
      </div>
    </div>
    <div class="col-md-3">
      <div class="card border-0 shadow-sm">
        <div class="card-body">
          <h5 class="card-title text-danger"><i class="bi bi-chat-dots"></i> Comentários</h5>
          <p class="card-text display-6 fw-bold"><?= $totalComentariosPendentes ?></p>
          <a href="#" data-page="gerenciar_comentarios" class="stretched-link">Moderar</a>
        </div>
      </div>
    </div>
  </div>
</div>

<style>
.card-title i {
  margin-right: 0.5rem;
}
</style>
