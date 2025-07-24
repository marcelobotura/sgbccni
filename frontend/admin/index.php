<?php
// Caminho: frontend/admin/index.php

session_start();
if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_tipo'] !== 'admin') {
    header('Location: ../../login/login_admin.php');
    exit;
}

define('BASE_PATH', dirname(__DIR__, 2));
require_once BASE_PATH . '/backend/config/config.php';
?>
<!DOCTYPE html>
<html lang="pt-br" data-tema="<?= htmlspecialchars($_COOKIE['modo_tema'] ?? 'claro') ?>">
<head>
  <meta charset="UTF-8">
  <title>Painel Admin - <?= NOME_SISTEMA ?></title>
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- Bootstrap e Ícones -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
  <link rel="stylesheet" href="<?= URL_BASE ?>frontend/assets/css/pages/painel_admin.css">
</head>
<body class="bg-light">

<div class="container py-5">
  <h1 class="text-center mb-4 text-primary"><i class="bi bi-speedometer2"></i> Painel Administrativo</h1>
  <div class="row g-4">
  
    <!-- 📚 Livros -->
    <div class="col-md-4">
      <a href="pages/gerenciar_livros.php" class="text-decoration-none">
        <div class="card text-center shadow-sm h-100">
          <div class="card-body">
            <i class="bi bi-book-half fs-1 text-info"></i>
            <h5 class="card-title mt-2">Livros</h5>
            <p class="text-muted small">Gerencie o acervo cadastrado.</p>
          </div>
        </div>
      </a>
    </div>

    <!-- 👥 Usuários -->
    <div class="col-md-4">
      <a href="pages/gerenciar_usuarios.php" class="text-decoration-none">
        <div class="card text-center shadow-sm h-100">
          <div class="card-body">
            <i class="bi bi-people fs-1 text-success"></i>
            <h5 class="card-title mt-2">Usuários</h5>
            <p class="text-muted small">Contas de usuários e administradores.</p>
          </div>
        </div>
      </a>
    </div>

    <!-- 🏷️ Tags -->
    <div class="col-md-4">
      <a href="pages/gerenciar_tags.php" class="text-decoration-none">
        <div class="card text-center shadow-sm h-100">
          <div class="card-body">
            <i class="bi bi-tags fs-1 text-warning"></i>
            <h5 class="card-title mt-2">Tags</h5>
            <p class="text-muted small">Autores, categorias, editoras...</p>
          </div>
        </div>
      </a>
    </div>

    <!-- 💬 Comentários -->
    <div class="col-md-4">
      <a href="pages/gerenciar_comentarios.php" class="text-decoration-none">
        <div class="card text-center shadow-sm h-100">
          <div class="card-body">
            <i class="bi bi-chat-left-dots fs-1 text-danger"></i>
            <h5 class="card-title mt-2">Comentários</h5>
            <p class="text-muted small">Moderação de comentários dos livros.</p>
          </div>
        </div>
      </a>
    </div>

    <!-- ✉️ Mensagens -->
    <div class="col-md-4">
      <a href="pages/gerenciar_mensagens.php" class="text-decoration-none">
        <div class="card text-center shadow-sm h-100">
          <div class="card-body">
            <i class="bi bi-envelope fs-1 text-secondary"></i>
            <h5 class="card-title mt-2">Mensagens</h5>
            <p class="text-muted small">Contatos e formulários recebidos.</p>
          </div>
        </div>
      </a>
    </div>

    <!-- 💡 Sugestões -->
    <div class="col-md-4">
      <a href="pages/gerenciar_sugestoes.php" class="text-decoration-none">
        <div class="card text-center shadow-sm h-100">
          <div class="card-body">
            <i class="bi bi-lightbulb fs-1 text-warning"></i>
            <h5 class="card-title mt-2">Sugestões</h5>
            <p class="text-muted small">Ideias e colaborações dos leitores.</p>
          </div>
        </div>
      </a>
    </div>

    <!-- 📊 Relatórios -->
    <div class="col-md-4">
      <a href="pages/gerenciar_relatorios.php" class="text-decoration-none">
        <div class="card text-center shadow-sm h-100">
          <div class="card-body">
            <i class="bi bi-graph-up-arrow fs-1 text-primary"></i>
            <h5 class="card-title mt-2">Relatórios</h5>
            <p class="text-muted small">Estatísticas e exportações.</p>
          </div>
        </div>
      </a>
    </div>

    <!-- 🗂️ Arquivos -->
    <div class="col-md-4">
      <a href="pages/gerenciar_arquivos.php" class="text-decoration-none">
        <div class="card text-center shadow-sm h-100">
          <div class="card-body">
            <i class="bi bi-folder2-open fs-1 text-info"></i>
            <h5 class="card-title mt-2">Arquivos</h5>
            <p class="text-muted small">Uploads de capas, perfis e mais.</p>
          </div>
        </div>
      </a>
    </div>

    <!-- 🗄️ Backup -->
    <div class="col-md-4">
      <a href="pages/backup.php" class="text-decoration-none">
        <div class="card text-center shadow-sm h-100">
          <div class="card-body">
            <i class="bi bi-database-check fs-1 text-success"></i>
            <h5 class="card-title mt-2">Backup</h5>
            <p class="text-muted small">Criação e restauração de dados.</p>
          </div>
        </div>
      </a>
    </div>

    <!-- 🌐 Mapa do Sistema -->
    <div class="col-md-4">
      <a href="pages/mapa_sistema.php" class="text-decoration-none">
        <div class="card text-center shadow-sm h-100">
          <div class="card-body">
            <i class="bi bi-diagram-3 fs-1 text-dark"></i>
            <h5 class="card-title mt-2">Mapa do Sistema</h5>
            <p class="text-muted small">Visualize as páginas e estruturas.</p>
          </div>
        </div>
      </a>
    </div>

    <!-- 🔐 Sair -->
    <div class="col-md-4">
      <a href="<?= URL_BASE ?>logout.php" class="text-decoration-none">
        <div class="card text-center shadow-sm h-100 border-danger">
          <div class="card-body">
            <i class="bi bi-box-arrow-right fs-1 text-danger"></i>
            <h5 class="card-title mt-2">Sair</h5>
            <p class="text-muted small">Encerrar sessão do administrador.</p>
          </div>
        </div>
      </a>
    </div>
    
  </div>
</div>

</body>
</html>
