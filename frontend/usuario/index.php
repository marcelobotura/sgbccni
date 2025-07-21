<?php
// Caminho: frontend/usuario/index.php

// 🔧 Exibir erros em desenvolvimento (remova em produção)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// ✅ Caminho base correto
define('BASE_PATH', realpath(__DIR__ . '/../../'));

// 🔐 Includes essenciais
require_once BASE_PATH . '/backend/config/config.php';
require_once BASE_PATH . '/backend/includes/session.php';

require_once BASE_PATH . '/backend/includes/header.php';

// 🔒 Exige login do tipo usuário
exigir_login('usuario');

// 👤 Dados do usuário logado
$nome = htmlspecialchars($_SESSION['usuario_nome'] ?? 'Usuário');
$email = htmlspecialchars($_SESSION['usuario_email'] ?? 'sem_email@exemplo.com');
$foto = $_SESSION['usuario_foto'] ?? null;
$caminhoFoto = $foto ? URL_BASE . 'uploads/perfis/' . $foto : URL_BASE . 'frontend/assets/img/avatar_padrao.png';
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <title>Painel do Usuário - <?= NOME_SISTEMA ?></title>
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- Fontes e ícones -->
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
  <link rel="icon" href="<?= URL_BASE ?>frontend/assets/img/favicon.png" type="image/png">

  <!-- CSS Global -->
  <link rel="stylesheet" href="<?= URL_BASE ?>frontend/assets/css/base.css">
  <link rel="stylesheet" href="<?= URL_BASE ?>frontend/assets/css/layout.css">
  <link rel="stylesheet" href="<?= URL_BASE ?>frontend/assets/css/components.css">
  <link rel="stylesheet" href="<?= URL_BASE ?>frontend/assets/css/pages/painel_usuario.css">
  <link rel="stylesheet" href="<?= URL_BASE ?>frontend/assets/css/themes/light.css" id="theme-style">
</head>
<body>

<main class="painel-usuario container">
  <!-- 👤 Cabeçalho do usuário -->
  <header class="painel-header d-flex justify-content-between align-items-center my-4 flex-wrap">
    <div class="d-flex align-items-center gap-3">
      <img src="<?= $caminhoFoto ?>" alt="Foto de perfil" class="rounded-circle border" style="width: 60px; height: 60px; object-fit: cover;">
      <div>
        <h2 class="mb-0"><?= $nome ?> 👋</h2>
        <small class="text-muted"><?= $email ?></small>
      </div>
    </div>
    
    <a href="../../backend/controllers/autenticacao/logout.php" class="btn btn-erro mt-3 mt-md-0">
      <i class="bi bi-box-arrow-right"></i> Sair
    </a>
  </header>

  <!-- 🔖 Cards principais -->
  <section class="painel-cards grid gap-4">
    <article class="card">
      <h3 class="card-title">🔍 Pesquisar Livros</h3>
      <p>Explore o acervo da biblioteca.</p>
      <a href="<?= URL_BASE ?>frontend/usuario/pesquisa.php" class="btn btn-primario">
        <i class="bi bi-search"></i> Buscar
      </a>
    </article>

    <article class="card">
      <h3 class="card-title">📖 Meus Empréstimos</h3>
      <p>Acompanhe os livros que você está lendo.</p>
      <a href="<?= URL_BASE ?>frontend/usuario/historico_leitura.php" class="btn btn-primario">
        <i class="bi bi-book-half"></i> Visualizar
      </a>
    </article>

    <article class="card">
      <h3 class="card-title">⭐ Favoritos</h3>
      <p>Seus livros favoritos em um só lugar.</p>
      <a href="<?= URL_BASE ?>frontend/usuario/meus_favoritos.php" class="btn btn-primario">
        <i class="bi bi-star-fill"></i> Ver Favoritos
      </a>
    </article>

    <article class="card">
      <h3 class="card-title">📊 Relatórios</h3>
      <p>Veja estatísticas da sua leitura.</p>
      <a href="<?= URL_BASE ?>frontend/usuario/relatorios.php" class="btn btn-secundario">
        <i class="bi bi-graph-up"></i> Ver Relatórios
      </a>
    </article>

    <article class="card">
      <h3 class="card-title">⚙️ Conta</h3>
      <p>Atualize seus dados pessoais.</p>
      <a href="<?= URL_BASE ?>frontend/usuario/editar_conta.php" class="btn btn-secundario">
        <i class="bi bi-pencil"></i> Editar Conta
      </a>
    </article>
  </section>
</main>

<?php require_once BASE_PATH . '/backend/includes/footer.php'; ?>
</body>
</html>
