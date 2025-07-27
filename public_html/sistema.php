<?php
// Caminho: frontend/public/sistema.php
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

  <!-- Estilos e ícones -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
  <link rel="stylesheet" href="<?= URL_BASE ?>frontend/assets/css/layout.css">
  <link rel="stylesheet" href="<?= URL_BASE ?>frontend/assets/css/pages/public.css">
</head>
<body class="bg-light d-flex flex-column min-vh-100">

  <!-- 🔝 Cabeçalho -->
  <header class="bg-white shadow-sm py-3 mb-4">
    <div class="container d-flex justify-content-between align-items-center">
      <a href="index.php" class="text-decoration-none text-primary fs-4 fw-bold">
        📚 <?= NOME_SISTEMA ?>
      </a>
      <nav>
        <a href="index.php" class="btn btn-link">Início</a>
        <a href="sobre.php" class="btn btn-link">Sobre</a>
        <a href="post/index.php" class="btn btn-link">Portagens</a>
        <a href="sistema.php" class="btn btn-link fw-bold text-primary">Sistema</a>
        <a href="contato.php" class="btn btn-link">Contato</a>
        <a href="login.php" class="btn btn-primary">
          <i class="bi bi-box-arrow-in-right"></i> Entrar</a>
        <i class="bi bi-moon-stars-fill tema-toggle ms-3" id="tema-toggle" title="Alternar tema"></i>
      </nav>
    </div>
  </header>

  <!-- ℹ️ Conteúdo principal -->
  <main class="container flex-grow-1 pb-5">
    <div class="card shadow-sm p-4 bg-white">
      <h2 class="text-primary">⚙️ Sobre o Sistema</h2>
      <p class="mt-3">
        Este sistema foi desenvolvido para gerenciar uma biblioteca comunitária de forma eficiente e moderna, com recursos que abrangem desde o cadastro de livros físicos e digitais até a interação com os leitores.
      </p>

      <hr>
      <ul class="list-group list-group-flush">
        <li class="list-group-item"><strong>Nome do sistema:</strong> <?= NOME_SISTEMA ?></li>
        <li class="list-group-item"><strong>Versão:</strong> <?= VERSAO_SISTEMA ?? '2.0' ?></li>
        <li class="list-group-item"><strong>Criador:</strong> Marcelo Botura Souza</li>
        <li class="list-group-item"><strong>Última atualização:</strong> <?= date('d/m/Y') ?></li>
      </ul>

      <h5 class="mt-4">🛠️ Melhorias realizadas em <?= date('d/m/Y') ?></h5>
      <ul class="list-group list-group-flush mt-2">
        <li class="list-group-item">Novo painel administrativo com SPA (Single Page Application), usando AJAX para carregar conteúdos dinamicamente.</li>
        <li class="list-group-item">Sidebar fixa com ícones modernos e menus organizados por seções (Usuários, Livros, Comentários, Mensagens, Logs, Sistema).</li>
        <li class="list-group-item">Página inicial <code>index.php</code> atualizada com carrosséis de livros: <strong>Destaques</strong>, <strong>Novas Aquisições</strong>, <strong>Mais Lidos</strong> e <strong>LivroCast</strong>.</li>
        <li class="list-group-item">Busca inteligente integrada com AJAX, exibindo resultados instantâneos e ocultando carrosséis durante a pesquisa.</li>
        <li class="list-group-item">Cadastro de livros reformulado: ISBN-10, ISBN-13, código interno, volume, edição, ano, capa por upload ou URL, link de leitura e QR Code automático.</li>
        <li class="list-group-item">Integração com APIs externas (Google Books e OpenLibrary) para preenchimento automático de dados via ISBN.</li>
        <li class="list-group-item">Sistema de Tags unificado (autor, categoria, editora, tipo, formato, volume, edição) com autocomplete via <code>Select2</code> e criação dinâmica.</li>
        <li class="list-group-item">Sistema de login reformulado para usuários e administradores com senha criptografada e visual unificado.</li>
        <li class="list-group-item">Perfil do usuário com opção de editar nome, e-mail, senha e foto de perfil com preview e validação.</li>
        <li class="list-group-item">Sistema de favoritos e leitura concluída por usuário, com histórico exportável em PDF/CSV.</li>
        <li class="list-group-item">Página pública com visual moderno e responsivo, tema claro/escuro, barra de busca persistente e login necessário para ações.</li>
        <li class="list-group-item">Módulo de comentários por livro com moderação no painel admin (aprovar/excluir) e filtro por status.</li>
        <li class="list-group-item">Logs completos de atividades: login, redefinições de senha, visualizações de livros e ações administrativas.</li>
        <li class="list-group-item">Sistema de mensagens com página pública de contato, painel de leitura/administração de mensagens recebidas.</li>
        <li class="list-group-item">Página <code>dashboard.php</code> criada como tela de boas-vindas com cartões de métricas: usuários, livros, favoritos e comentários pendentes.</li>
        <li class="list-group-item">Nova organização de pastas com separação profissional entre <code>frontend</code> e <code>backend</code>, estrutura CSS modular e rotas dinâmicas com <code>URL_BASE</code>.</li>
      </ul>
    </div>
  </main>

  <!-- 🔚 Rodapé -->
  <footer class="bg-white text-center text-muted py-3 mt-auto border-top">
    <small>&copy; <?= date('Y') ?> <?= NOME_SISTEMA ?> — Desenvolvido por Marcelo Botura Souza</small>
  </footer>

  <script>
    // Alternância de tema (claro/escuro)
    document.getElementById('tema-toggle')?.addEventListener('click', () => {
      const html = document.documentElement;
      const temaAtual = html.getAttribute('data-tema') === 'escuro' ? 'claro' : 'escuro';
      html.setAttribute('data-tema', temaAtual);
      document.cookie = `modo_tema=${temaAtual};path=/;max-age=31536000`;
    });
  </script>
</body>
</html>
