<?php
// Caminho: frontend/usuario/index.php

declare(strict_types=1); // Deve ser a PRIMEIRA LINHA antes de qualquer outra

// 🔧 Exibir erros em desenvolvimento (remova em produção)
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

// ✅ Caminho base correto
define('BASE_PATH', realpath(__DIR__ . '/../../'));

// 🔐 Includes essenciais
require_once BASE_PATH . '/backend/config/config.php';
require_once BASE_PATH . '/backend/includes/session.php';
require_once BASE_PATH . '/backend/includes/header.php';

// 🔒 Exige login do tipo usuário
exigir_login('usuario');

// 👤 Pega dados mais recentes do banco
$id_usuario = $_SESSION['usuario_id'] ?? null;
if (!$id_usuario) {
  header("Location: login.php");
  exit;
}

$stmt = $pdo->prepare("SELECT nome, email, foto FROM usuarios WHERE id = ?");
$stmt->execute([$id_usuario]);
$usuario = $stmt->fetch(PDO::FETCH_ASSOC);

$nome  = htmlspecialchars($usuario['nome'] ?? 'Usuário');
$email = htmlspecialchars($usuario['email'] ?? 'sem_email@exemplo.com');
$foto  = $usuario['foto'] ?? null;

// 📸 Caminho da imagem de perfil com fallback
$caminhoFisicoFoto = BASE_PATH . '/storage/uploads/perfis/' . $foto;
$caminhoWebFoto    = URL_BASE . 'storage/uploads/perfis/' . $foto;
$caminhoFoto       = (!empty($foto) && file_exists($caminhoFisicoFoto)) ? $caminhoWebFoto : URL_BASE . 'frontend/assets/img/perfil_sem_img.png';
?>
<!DOCTYPE html>
<html lang="pt-br" data-theme="<?= htmlspecialchars($_COOKIE['tema'] ?? 'light') ?>">
<head>
  <meta charset="UTF-8">
  <title>Painel do Usuário - <?= NOME_SISTEMA ?></title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
  <link rel="icon" href="<?= URL_BASE ?>frontend/assets/img/favicon.png" type="image/png">

  <link rel="stylesheet" href="<?= URL_BASE ?>frontend/assets/css/base.css">
  <link rel="stylesheet" href="<?= URL_BASE ?>frontend/assets/css/layout.css">
  <link rel="stylesheet" href="<?= URL_BASE ?>frontend/assets/css/components.css">
  <link rel="stylesheet" href="<?= URL_BASE ?>frontend/assets/css/pages/painel_user.css">
  <link rel="stylesheet" href="<?= URL_BASE ?>frontend/assets/css/themes/user.css">
  <link rel="stylesheet" href="<?= URL_BASE ?>frontend/assets/css/themes/light.css">
  <link rel="stylesheet" href="<?= URL_BASE ?>frontend/assets/css/themes/dark.css">
  <link rel="stylesheet" href="<?= URL_BASE ?>frontend/assets/css/themes/high-contrast.css">
</head>
<body>

<!-- 🌙 Botão de alternância de tema -->
<button class="toggle-theme" onclick="alternarTema()">🌓 Tema</button>

<main class="painel-usuario container py-4">
  <header class="painel-header d-flex justify-content-between align-items-center mb-4 flex-wrap">
    <div class="d-flex align-items-center gap-3">
      <img src="<?= $caminhoFoto ?>" alt="Foto de perfil" class="rounded-circle border" style="width: 60px; height: 60px; object-fit: cover;">
      <div>
        <h2 class="mb-0">Olá, <?= $nome ?> 👋</h2>
        <small class="text-muted"><?= $email ?></small>
      </div>
    </div>
    <a href="<?= URL_BASE ?>backend/controllers/autenticacao/logout.php" class="btn btn-erro mt-3 mt-md-0">
      <i class="bi bi-box-arrow-right"></i> Sair
    </a>
  </header>

  <!-- 🔖 Cards principais -->
  <section class="painel-cards row g-4">
    <div class="col-md-6 col-lg-4">
      <article class="card h-100">
        <div class="card-body">
          <h3 class="card-title">🔍 Pesquisar Livros</h3>
          <p class="card-text">Explore o acervo da biblioteca.</p>
          <a href="<?= URL_BASE ?>frontend/usuario/pesquisa.php" class="btn btn-primario">
            <i class="bi bi-search"></i> Buscar
          </a>
        </div>
      </article>
    </div>

    <div class="col-md-6 col-lg-4">
      <article class="card h-100">
        <div class="card-body">
          <h3 class="card-title">📖 Meus Empréstimos</h3>
          <p class="card-text">Acompanhe os livros que você está lendo.</p>
          <a href="<?= URL_BASE ?>frontend/usuario/historico_leitura.php" class="btn btn-primario">
            <i class="bi bi-book-half"></i> Visualizar
          </a>
        </div>
      </article>
    </div>

    <div class="col-md-6 col-lg-4">
      <article class="card h-100">
        <div class="card-body">
          <h3 class="card-title">⭐ Favoritos</h3>
          <p class="card-text">Seus livros favoritos em um só lugar.</p>
          <a href="<?= URL_BASE ?>frontend/usuario/meus_favoritos.php" class="btn btn-primario">
            <i class="bi bi-star-fill"></i> Ver Favoritos
          </a>
        </div>
      </article>
    </div>

    <div class="col-md-6 col-lg-4">
      <article class="card h-100">
        <div class="card-body">
          <h3 class="card-title">📊 Relatórios</h3>
          <p class="card-text">Veja estatísticas da sua leitura.</p>
          <a href="<?= URL_BASE ?>frontend/usuario/relatorios.php" class="btn btn-secundario">
            <i class="bi bi-graph-up"></i> Ver Relatórios
          </a>
        </div>
      </article>
    </div>

    <div class="col-md-6 col-lg-4">
      <article class="card h-100">
        <div class="card-body">
          <h3 class="card-title">👤 Perfil</h3>
          <p class="card-text">Atualize seus dados pessoais.</p>
          <a href="<?= URL_BASE ?>frontend/usuario/ver_perfil.php" class="btn btn-secundario">
            <i class="bi bi-pencil"></i> Ver Perfil
          </a>
        </div>
      </article>
    </div>
  </section>
</main>

<script>
function alternarTema() {
  const atual = document.documentElement.getAttribute('data-theme') || 'light';
  const novo = atual === 'dark' ? 'light' : 'dark';
  document.documentElement.setAttribute('data-theme', novo);
  document.cookie = `tema=${novo}; path=/; max-age=31536000`;
}
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
