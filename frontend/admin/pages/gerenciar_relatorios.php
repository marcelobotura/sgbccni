<?php
// Caminho: frontend/admin/pages/gerenciar_relatorios.php

define('BASE_PATH', dirname(__DIR__, 3));
require_once BASE_PATH . '/backend/config/config.php';
require_once BASE_PATH . '/backend/includes/session.php';
require_once BASE_PATH . '/backend/includes/protect_admin.php';
require_once BASE_PATH . '/backend/includes/header.php';
require_once BASE_PATH . '/backend/includes/menu.php';

exigir_login('admin');
?>

<div class="container py-4">
  <h2 class="mb-4"><i class="bi bi-file-earmark-text"></i> Relatórios do Sistema</h2>

  <div class="row g-4">

    <!-- Livros Lidos -->
    <div class="col-md-6 col-xl-4">
      <div class="card shadow-sm border-start border-4 border-primary h-100">
        <div class="card-body d-flex flex-column">
          <div class="d-flex align-items-center mb-2 text-primary">
            <i class="bi bi-book-half fs-3 me-2"></i>
            <h5 class="card-title mb-0">Livros Lidos</h5>
          </div>
          <p class="card-text">Visualize as leituras realizadas por cada usuário do sistema.</p>
          <a href="relatorios/livros_lidos.php" class="btn btn-outline-primary mt-auto">Ver Relatório</a>
        </div>
      </div>
    </div>

    <!-- Mais Lidos -->
    <div class="col-md-6 col-xl-4">
      <div class="card shadow-sm border-start border-4 border-success h-100">
        <div class="card-body d-flex flex-column">
          <div class="d-flex align-items-center mb-2 text-success">
            <i class="bi bi-star-fill fs-3 me-2"></i>
            <h5 class="card-title mb-0">Mais Lidos</h5>
          </div>
          <p class="card-text">Ranking dos livros mais lidos, com base em interações reais.</p>
          <a href="relatorios/mais_lidos.php" class="btn btn-outline-success mt-auto">Ver Relatório</a>
        </div>
      </div>
    </div>

    <!-- Favoritos -->
    <div class="col-md-6 col-xl-4">
      <div class="card shadow-sm border-start border-4 border-warning h-100">
        <div class="card-body d-flex flex-column">
          <div class="d-flex align-items-center mb-2 text-warning">
            <i class="bi bi-heart-fill fs-3 me-2"></i>
            <h5 class="card-title mb-0">Favoritos</h5>
          </div>
          <p class="card-text">Consulta dos livros favoritos mais marcados pelos usuários.</p>
          <a href="relatorios/favoritos.php" class="btn btn-outline-warning mt-auto">Ver Relatório</a>
        </div>
      </div>
    </div>

    <!-- Categorias -->
    <div class="col-md-6 col-xl-4">
      <div class="card shadow-sm border-start border-4 border-info h-100">
        <div class="card-body d-flex flex-column">
          <div class="d-flex align-items-center mb-2 text-info">
            <i class="bi bi-diagram-3-fill fs-3 me-2"></i>
            <h5 class="card-title mb-0">Por Categoria</h5>
          </div>
          <p class="card-text">Análise das leituras por categorias cadastradas no sistema.</p>
          <a href="relatorios/categorias.php" class="btn btn-outline-info mt-auto">Ver Relatório</a>
        </div>
      </div>
    </div>

    <!-- Editoras -->
    <div class="col-md-6 col-xl-4">
      <div class="card shadow-sm border-start border-4 border-secondary h-100">
        <div class="card-body d-flex flex-column">
          <div class="d-flex align-items-center mb-2 text-secondary">
            <i class="bi bi-building fs-3 me-2"></i>
            <h5 class="card-title mb-0">Por Editora</h5>
          </div>
          <p class="card-text">Visualize quantas leituras foram feitas por editora.</p>
          <a href="relatorios/editoras.php" class="btn btn-outline-secondary mt-auto">Ver Relatório</a>
        </div>
      </div>
    </div>

    <!-- Exportações -->
    <div class="col-md-6 col-xl-4">
      <div class="card shadow-sm border-start border-4 border-dark h-100">
        <div class="card-body d-flex flex-column">
          <div class="d-flex align-items-center mb-2 text-dark">
            <i class="bi bi-download fs-3 me-2"></i>
            <h5 class="card-title mb-0">Exportações</h5>
          </div>
          <p class="card-text">Gere arquivos PDF ou CSV de todos os relatórios disponíveis.</p>
          <a href="relatorios/exportar.php" class="btn btn-outline-dark mt-auto">Exportar Dados</a>
        </div>
      </div>
    </div>

  </div>
</div>
