<?php
// Caminho: frontend/admin/pages/dashboard.php

define('BASE_PATH', dirname(__DIR__, 3));
require_once BASE_PATH . '/backend/config/config.php';
require_once BASE_PATH . '/backend/includes/session.php';
require_once BASE_PATH . '/backend/includes/protect_admin.php';
require_once BASE_PATH . '/backend/includes/header.php';

exigir_login('admin');

$nome_admin = $_SESSION['usuario_nome'] ?? 'Administrador';
$data_hoje = date('d/m/Y');
$hora_atual = date('H:i');

// 🔢 Coletar dados reais do banco
$total_usuarios = $pdo->query("SELECT COUNT(*) FROM usuarios")->fetchColumn();
$total_livros = $pdo->query("SELECT COUNT(*) FROM livros")->fetchColumn();
$total_favoritos = $pdo->query("SELECT COUNT(*) FROM livros_usuarios WHERE status = 'favorito'")->fetchColumn();
$total_comentarios_pendentes = $pdo->query("SELECT COUNT(*) FROM comentarios WHERE aprovado = 0")->fetchColumn();
?>

<div class="container py-4">
  <!-- Boas-vindas aprimorada -->
  <div class="p-4 rounded shadow mb-4 text-white" style="background: linear-gradient(135deg, #1d3557, #457b9d); position: relative; overflow: hidden;">
    <div class="d-flex justify-content-between align-items-center flex-wrap">
      <div>
        <h1 class="fw-bold mb-1"><i class="bi bi-person-circle me-2"></i>Olá, <?= htmlspecialchars($nome_admin); ?> 👋</h1>
        <p class="mb-2">Você acessou o painel da <strong>Biblioteca Comunitária CNI</strong></p>
        <span class="badge bg-light text-dark px-3 py-2"><i class="bi bi-calendar-event me-1"></i> <?= $data_hoje ?> | <i class="bi bi-clock me-1"></i> <?= $hora_atual ?></span>
      </div>
      <div class="d-none d-md-block">
        <i class="bi bi-journal-code" style="font-size: 4rem; opacity: 0.2;"></i>
      </div>
    </div>
  </div>

  <!-- Cartões de Métricas -->
  <div class="row g-4 mb-4">
    <div class="col-md-3">
      <div class="card text-center shadow-sm border-info">
        <div class="card-body">
          <i class="bi bi-people fs-3 text-info"></i>
          <h5 class="card-title mt-2">Usuários</h5>
          <p class="display-6 fw-bold"><?= $total_usuarios ?></p>
        </div>
      </div>
    </div>
    <div class="col-md-3">
      <div class="card text-center shadow-sm border-success">
        <div class="card-body">
          <i class="bi bi-journal-text fs-3 text-success"></i>
          <h5 class="card-title mt-2">Livros</h5>
          <p class="display-6 fw-bold"><?= $total_livros ?></p>
        </div>
      </div>
    </div>
    <div class="col-md-3">
      <div class="card text-center shadow-sm border-warning">
        <div class="card-body">
          <i class="bi bi-star fs-3 text-warning"></i>
          <h5 class="card-title mt-2">Favoritos</h5>
          <p class="display-6 fw-bold"><?= $total_favoritos ?></p>
        </div>
      </div>
    </div>
    <div class="col-md-3">
      <div class="card text-center shadow-sm border-danger">
        <div class="card-body">
          <i class="bi bi-chat-dots fs-3 text-danger"></i>
          <h5 class="card-title mt-2">Pendentes</h5>
          <p class="display-6 fw-bold"><?= $total_comentarios_pendentes ?></p>
        </div>
      </div>
    </div>
  </div>

  <!-- Acessos rápidos -->
  <div class="row g-3">
    <div class="col-md-3">
      <a href="pages/gerenciar_usuarios.php" class="btn btn-outline-primary w-100"><i class="bi bi-people"></i> Gerenciar Usuários</a>
    </div>
    <div class="col-md-3">
      <a href="pages/gerenciar_livros.php" class="btn btn-outline-success w-100"><i class="bi bi-journal-text"></i> Gerenciar Livros</a>
    </div>
    <div class="col-md-3">
      <a href="pages/gerenciar_mensagens.php" class="btn btn-outline-secondary w-100"><i class="bi bi-chat-dots"></i> Mensagens</a>
    </div>
    <div class="col-md-3">
      <a href="pages/gerenciar_relatorios.php" class="btn btn-outline-warning w-100"><i class="bi bi-bar-chart-line"></i> Relatórios</a>
    </div>
  </div>

  <div class="text-center mt-5">
    <small class="text-muted">📚 Sistema Biblioteca CNI – Desenvolvido por Marcelo Botura Souza</small>
  </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
