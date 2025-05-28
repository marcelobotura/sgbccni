<?php
// Define o caminho base para a pasta 'backend', um nível acima da 'public_html'
// O dirname(__DIR__) garante que ele volte para a pasta 'sgbccni' antes de ir para 'backend'.
define('BASE_PATH', dirname(__DIR__) . '/backend');

// Inclui os arquivos de configuração e sessão essenciais
// O config.php deve definir URL_BASE e outras constantes globais.
require_once BASE_PATH . '/config/config.php';
// O session.php deve iniciar a sessão (session_start()) e conter funções de sessão.
require_once BASE_PATH . '/includes/session.php';

// 🔒 Garante que o usuário esteja logado
// A função exigir_login() deve redirecionar o usuário caso não esteja logado.
exigir_login('usuario');

// O arquivo header.php deve conter o DOCTYPE, <html>, <head> (com meta, title, links CSS) e o <body> de abertura.
// Se seu header.php já inclui tudo isso, você pode remover as tags HTML abaixo que foram comentadas.
require_once BASE_PATH . '/includes/header.php';
?>

<div class="container py-5">
  <div class="row justify-content-between align-items-center mb-4">
    <div class="col-md-8">
      <h2 class="fw-bold">👋 Olá, <?= htmlspecialchars($_SESSION['usuario_nome'] ?? 'Visitante') ?></h2>
      <p class="text-muted">Bem-vindo à sua área da <strong>Biblioteca Comunitária CNI</strong>.</p>
    </div>
    <div class="col-md-4 text-end">
      <a href="<?= URL_BASE ?>logout.php" class="btn btn-outline-danger">
        <i class="bi bi-box-arrow-right"></i> Sair
      </a>
    </div>
  </div>

  <div class="row g-4">
    <div class="col-md-4">
      <div class="card shadow-sm border-0 h-100">
        <div class="card-body text-center">
          <h5 class="card-title">📚 Meus Livros</h5>
          <p class="card-text">Veja seus livros lidos, favoritos e observações.</p>
          <a href="<?= URL_BASE ?>frontend/usuario/meus_livros.php" class="btn btn-primary w-100">Acessar</a>
        </div>
      </div>
    </div>

    <div class="col-md-4">
      <div class="card shadow-sm border-0 h-100">
        <div class="card-body text-center">
          <h5 class="card-title">👤 Meu Perfil</h5>
          <p class="card-text">Atualize seus dados, imagem e senha.</p>
          <a href="<?= URL_BASE ?>frontend/usuario/perfil.php" class="btn btn-secondary w-100">Acessar</a>
        </div>
      </div>
    </div>

    <div class="col-md-4">
      <div class="card shadow-sm border-0 h-100">
        <div class="card-body text-center">
          <h5 class="card-title">💡 Sugestões</h5>
          <p class="card-text">Envie ideias de livros ou melhorias para o sistema.</p>
          <a href="<?= URL_BASE ?>frontend/usuario/sugestao.php" class="btn btn-outline-success w-100">Enviar</a>
        </div>
      </div>
    </div>
  </div>
</div>

<?php 
// O arquivo footer.php deve conter o fechamento do <body> e <html>, além de scripts JS.
require_once BASE_PATH . '/includes/footer.php'; 
?>