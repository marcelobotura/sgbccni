<?php
require_once __DIR__ . '/../backend/config/config.php';
require_once __DIR__ . '/../backend/includes/db.php';
require_once __DIR__ . '/../backend/includes/session.php';
?>
<!DOCTYPE html>
<html lang="pt-br" data-tema="<?= htmlspecialchars($_COOKIE['modo_tema'] ?? 'claro') ?>">
<head>
  <meta charset="UTF-8">
  <title>Sobre Nós - <?= NOME_SISTEMA ?></title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
  <link rel="stylesheet" href="<?= URL_BASE ?>frontend/assets/css/layout.css">
</head>
<body class="bg-light">

<div class="container py-4">
  <!-- 🔝 Cabeçalho com menu -->
  <header class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="fw-bold text-primary">📘 Sobre Nós</h2>
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

  <!-- ℹ️ Conteúdo institucional -->
  <div class="card shadow-sm p-4 bg-white">
    <h4 class="text-primary">Cidade Nova Informa – Biblioteca Comunitária</h4>
    <p>A Biblioteca Comunitária Cidade Nova Informa nasceu em 2011 como uma iniciativa de moradores do bairro Cidade Nova, em Foz do Iguaçu – PR. Seu objetivo é promover o acesso à leitura, educação, cultura e cidadania para crianças, jovens, adultos e idosos da comunidade.</p>

    <p>Ao longo dos anos, o projeto expandiu suas atividades e passou a integrar outras ações como rádio comunitária, espaço para a terceira idade (Espaço 60+), oficinas culturais e projetos de inclusão digital.</p>

    <p>Com o lançamento deste sistema digital, buscamos tornar o acervo da biblioteca acessível online, incentivando a leitura, o empréstimo e o compartilhamento de conhecimento.</p>

    <hr>
    <ul class="list-group list-group-flush">
      <li class="list-group-item"><strong>Fundação:</strong> 2011</li>
      <li class="list-group-item"><strong>Localização:</strong> Bairro Cidade Nova, Foz do Iguaçu – PR</li>
      <li class="list-group-item"><strong>Áreas de atuação:</strong> Educação, Cultura, Inclusão Digital, Leitura e Cidadania</li>
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
