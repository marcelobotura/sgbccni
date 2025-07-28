<?php
// Caminho: frontend/admin/index.php

session_start();

define('BASE_PATH', dirname(__DIR__, 2)); // /sgbccni
require_once BASE_PATH . '/backend/config/config.php';
require_once BASE_PATH . '/backend/includes/session.php';

// ✅ Garante que apenas admin/master acessem
if (!isset($_SESSION['usuario_id']) || !in_array($_SESSION['usuario_tipo'], ['admin', 'master'])) {
    header('Location: ' . URL_BASE . 'login.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt-br" data-tema="<?= htmlspecialchars($_COOKIE['modo_tema'] ?? 'claro') ?>">
<head>
  <meta charset="UTF-8">
  <title>Painel Admin - <?= NOME_SISTEMA ?></title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  
  <!-- Bootstrap & Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
  <link rel="stylesheet" href="<?= URL_BASE ?>frontend/assets/css/pages/painel_admin.css">

  <style>
    .text-purple { color: #6f42c1 !important; }
  </style>
</head>
<body class="bg-light">

<div class="container py-5">
  <h1 class="text-center mb-4 text-primary"><i class="bi bi-speedometer2"></i> Painel Administrativo</h1>
  <div class="row g-4">
  
    <!-- Blocos de Gerenciamento -->
    <?php
    $cards = [
      ['Empréstimos', 'pages/emprestimos.php', 'bi-arrow-left-right', 'Controle de empréstimos e devoluções.', 'primary'],
      ['Reservas', 'pages/reservas.php', 'bi-bookmark-check', 'Livros reservados por usuários.', 'info'],
      ['Livros', 'pages/gerenciar_livros.php', 'bi-book-half', 'Gerencie o acervo cadastrado.', 'info'],
      ['Usuários', 'pages/gerenciar_usuarios.php', 'bi-people', 'Contas de usuários e administradores.', 'success'],
      ['Tags', 'pages/gerenciar_tags.php', 'bi-tags', 'Autores, categorias, editoras...', 'warning'],
      ['Comentários', 'pages/gerenciar_comentarios.php', 'bi-chat-left-dots', 'Moderação de comentários dos livros.', 'danger'],
      ['Mensagens', 'pages/gerenciar_mensagens.php', 'bi-envelope', 'Contatos e formulários recebidos.', 'secondary'],
      ['Sugestões', 'pages/gerenciar_sugestoes.php', 'bi-lightbulb', 'Ideias e colaborações dos leitores.', 'warning'],
      ['Mídias', 'pages/gerenciar_midias.php', 'bi-play-circle', 'Vídeos, podcasts e conteúdo interativo.', 'purple'],
      ['Relatórios', 'pages/gerenciar_relatorios.php', 'bi-graph-up-arrow', 'Estatísticas e exportações.', 'primary'],
      ['Arquivos', 'pages/gerenciar_arquivos.php', 'bi-folder2-open', 'Uploads de capas, perfis e mais.', 'info'],
      ['Backup', 'pages/backup.php', 'bi-database-check', 'Criação e restauração de dados.', 'success'],
      ['Mapa do Sistema', 'pages/mapa_sistema.php', 'bi-diagram-3', 'Visualize as páginas e estruturas.', 'dark'],
      ['Sair', URL_BASE . 'logout.php', 'bi-box-arrow-right', 'Encerrar sessão do administrador.', 'danger']
    ];

    foreach ($cards as [$titulo, $link, $icone, $descricao, $cor]) {
      $classeCor = $cor === 'purple' ? 'text-purple' : "text-$cor";
      $borda     = in_array($cor, ['primary', 'danger']) ? "border-$cor" : '';
      echo <<<HTML
      <div class="col-md-4">
        <a href="$link" class="text-decoration-none">
          <div class="card text-center shadow-sm h-100 $borda">
            <div class="card-body">
              <i class="bi $icone fs-1 $classeCor"></i>
              <h5 class="card-title mt-2">$titulo</h5>
              <p class="text-muted small">$descricao</p>
            </div>
          </div>
        </a>
      </div>
      HTML;
    }
    ?>
  </div>
</div>

</body>
</html>
