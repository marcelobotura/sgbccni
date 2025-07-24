<?php
require_once __DIR__ . '/../backend/config/config.php';
require_once __DIR__ . '/../backend/includes/session.php';
require_once __DIR__ . '/../backend/includes/db.php';
?>
<!DOCTYPE html>
<html lang="pt-br" data-tema="<?= htmlspecialchars($_COOKIE['modo_tema'] ?? 'claro') ?>">
<head>
  <meta charset="UTF-8">
  <title>Sobre Nós - <?= NOME_SISTEMA ?></title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  
  <!-- Estilos principais -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
  <link rel="stylesheet" href="<?= URL_BASE ?>frontend/assets/css/layout.css">
  <link rel="stylesheet" href="<?= URL_BASE ?>frontend/assets/css/pages/public.css">
</head>
<body class="bg-light">

  <!-- 🔝 Cabeçalho -->
  <header class="bg-white shadow-sm py-3 mb-4">
    <div class="container d-flex justify-content-between align-items-center">
      <a href="index.php" class="text-decoration-none text-primary fs-4 fw-bold">
        📚 <?= NOME_SISTEMA ?>
      </a>
    <nav>
        <a href="index.php" class="btn btn-link">Início</a>
        <a href="sobre.php" class="btn btn-link fw-bold text-primary">Sobre</a>
        <a href="sistema.php" class="btn btn-link">Sistema</a>
        <a href="contato.php" class="btn btn-link">Contato</a>
        <a href="<?= URL_BASE ?>frontend/login/login.php" class="btn btn-primary"><i class="bi bi-box-arrow-in-right"></i> Entrar</a>
        <i class="bi bi-moon-stars-fill tema-toggle" id="tema-toggle"></i>
      </nav>
    </div>
  </header>

  <!-- ℹ️ Conteúdo principal -->
  <main class="container pb-5">
    <div class="card shadow-sm p-4">
      <h2 class="text-primary">📘 Sobre Nós</h2>
      <p class="mt-3">A <strong>Biblioteca Comunitária Cidade Nova Informa</strong> nasceu em 2011 como uma iniciativa de moradores do bairro Cidade Nova, em Foz do Iguaçu – PR. Seu objetivo é promover o acesso à leitura, educação, cultura e cidadania para crianças, jovens, adultos e idosos da comunidade.</p>

      <p>Ao longo dos anos, o projeto expandiu suas atividades e passou a integrar outras ações como rádio comunitária, espaço para a terceira idade (Espaço 60+), oficinas culturais e projetos de inclusão digital.</p>

      <p>Com o lançamento deste sistema digital, buscamos tornar o acervo da biblioteca acessível online, incentivando a leitura, o empréstimo e o compartilhamento de conhecimento.</p>

      <hr>
      <ul class="list-group list-group-flush">
        <li class="list-group-item"><strong>Fundação:</strong> 2011</li>
        <li class="list-group-item"><strong>Localização:</strong> Bairro Cidade Nova, Foz do Iguaçu – PR</li>
        <li class="list-group-item"><strong>Áreas de atuação:</strong> Educação, Cultura, Inclusão Digital, Leitura e Cidadania</li>
      </ul>
    </div>
  </main>


</body>
</html>
