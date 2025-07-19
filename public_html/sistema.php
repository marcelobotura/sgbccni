<?php
require_once __DIR__ . '/../backend/config/config.php';
require_once __DIR__ . '/../backend/includes/db.php';
require_once __DIR__ . '/../backend/includes/session.php';
?>
<!DOCTYPE html>
<html lang="pt-br" data-tema="<?= htmlspecialchars($_COOKIE['modo_tema'] ?? 'claro') ?>">
<head>
  <meta charset="UTF-8">
  <title>Informações do Sistema - <?= NOME_SISTEMA ?></title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
  <link rel="stylesheet" href="<?= URL_BASE ?>frontend/assets/css/layout.css">
  <style>
    html, body {
      height: 100%;
      margin: 0;
      display: flex;
      flex-direction: column;
    }

    .container {
      flex: 1;
    }

    footer {
      background: #212529;
      color: white;
      padding: 1rem;
      text-align: center;
    }
  </style>
</head>
<body class="bg-light">

<div class="container py-4">
  <!-- 🔝 Cabeçalho com menu igual ao index -->
  <header class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="fw-bold text-primary">⚙️ Sobre o Sistema</h2>
    <nav>
      <a href="index.php" class="btn btn-outline-primary me-2">Início</a>
      <a href="sobre.php" class="btn btn-outline-secondary me-2">Sobre Nós</a>
      <a href="contato.php" class="btn btn-outline-success me-2">Contato</a>
      <a href="sistema.php" class="btn btn-outline-dark me-2">Sistema</a>
      <a href="<?= URL_BASE ?>frontend/login/login_user.php" class="btn btn-primary me-1">
        <i class="bi bi-person"></i> Entrar
      </a>
      <a href="<?= URL_BASE ?>frontend/login/register_user.php" class="btn btn-success">
        <i class="bi bi-person-plus"></i> Criar Conta
      </a>
    </nav>
  </header>

  <!-- ℹ️ Conteúdo do sistema -->
  <div class="card shadow-sm p-4 bg-white">
    <h4 class="text-primary">📘 Sistema de Gestão de Biblioteca</h4>
    <p>Este sistema foi desenvolvido para gerenciar uma biblioteca comunitária, permitindo o cadastro de livros, controle de empréstimos, favoritos, leitura online e relatórios.</p>

    <hr>
    <ul class="list-group list-group-flush">
      <li class="list-group-item"><strong>Nome do sistema:</strong> <?= NOME_SISTEMA ?></li>
      <li class="list-group-item"><strong>Versão:</strong> 1.0</li>
      <li class="list-group-item"><strong>Criador:</strong> Marcelo Botura Souza</li>
      <li class="list-group-item"><strong>Última atualização:</strong> <?= date('d/m/Y') ?></li>
    </ul>

    <!-- ✅ Melhorias recentes -->
    <h5 class="mt-4">🛠️ Melhorias realizadas em <?= date('d/m/Y') ?></h5>
    <ul class="list-group list-group-flush mt-2">
      <li class="list-group-item">Novo painel administrativo com SPA (Single Page Application) usando AJAX para carregar páginas dinamicamente.</li>
      <li class="list-group-item">Sidebar fixa com todos os menus atualizados e ícones do Bootstrap Icons.</li>
      <li class="list-group-item">Página inicial <code>index.php</code> transformada em SPA com conteúdo carregado dentro da área branca principal.</li>
      <li class="list-group-item">Página <code>dashboard.php</code> criada como tela de boas-vindas, exibindo nome do administrador, data/hora e cartões de métricas (usuários, livros, favoritos, comentários pendentes).</li>
      <li class="list-group-item">Melhorias visuais com gradientes, cartões com ícones e contadores de dados atualizados dinamicamente do banco de dados.</li>
      <li class="list-group-item">Páginas internas ajustadas para usar novas rotas: <code>pages/gerenciar_usuarios.php</code>, <code>pages/gerenciar_livros.php</code>, etc.</li>
      <li class="list-group-item">Compatibilidade visual com tema claro/escuro via cookie.</li>
      <li class="list-group-item">Atualização visual completa no layout do painel administrativo sem perda de informação.</li>
    </ul>
  </div>
</div>

<!-- 📎 Rodapé fixo -->
<footer>
  <p class="mb-1">Sistema: <strong><?= NOME_SISTEMA ?></strong> | Versão 1.0</p>
  <p class="mb-1">Criado por <strong>Marcelo Botura Souza</strong></p>
  <p class="mb-0 small">&copy; <?= date('Y') ?> Todos os direitos reservados.</p>
</footer>

</body>
</html>
