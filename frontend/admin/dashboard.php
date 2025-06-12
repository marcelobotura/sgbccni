<?php
// 🛠️ Ativa exibição de erros (somente em desenvolvimento)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// 🔄 Includes essenciais
session_start();
define('BASE_PATH', dirname(__DIR__) . '/../backend');
require_once BASE_PATH . '/config/config.php';
require_once BASE_PATH . '/includes/session.php';
require_once BASE_PATH . '/includes/header.php';

exigir_login('admin');

// 🔢 Coleta estatísticas básicas (PDO)
$total_usuarios = $conn->query("SELECT COUNT(*) FROM usuarios")->fetchColumn();
$total_livros   = $conn->query("SELECT COUNT(*) FROM livros")->fetchColumn();
$total_msgs     = $conn->query("SELECT COUNT(*) FROM mensagens_contato")->fetchColumn();
$total_leituras = $conn->query("SELECT COUNT(*) FROM livros_usuarios")->fetchColumn();
?>

<!DOCTYPE html>
<html lang="pt-br" data-tema="<?= htmlspecialchars($_COOKIE['modo_tema'] ?? 'claro') ?>">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Painel Administrativo - <?= NOME_SISTEMA ?? 'Biblioteca CNI' ?></title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container py-4">
  <h2 class="mb-4">📊 Painel Administrativo</h2>

  <div class="row g-4">
    <div class="col-md-3">
      <div class="card text-white bg-primary shadow-sm">
        <div class="card-body">
          <h5 class="card-title">Usuários</h5>
          <p class="card-text fs-3"><?= $total_usuarios ?></p>
        </div>
        <div class="card-footer bg-transparent border-top-0">
          <a href="usuarios.php" class="text-white text-decoration-none">Gerenciar <i class="bi bi-chevron-right"></i></a>
        </div>
      </div>
    </div>

    <div class="col-md-3">
      <div class="card text-white bg-success shadow-sm">
        <div class="card-body">
          <h5 class="card-title">Livros</h5>
          <p class="card-text fs-3"><?= $total_livros ?></p>
        </div>
        <div class="card-footer bg-transparent border-top-0">
          <a href="listar_livros.php" class="text-white text-decoration-none">Ver todos <i class="bi bi-chevron-right"></i></a>
        </div>
      </div>
    </div>

    <div class="col-md-3">
      <div class="card text-white bg-warning shadow-sm">
        <div class="card-body">
          <h5 class="card-title">Leituras</h5>
          <p class="card-text fs-3"><?= $total_leituras ?></p>
        </div>
        <div class="card-footer bg-transparent border-top-0">
          <a href="listar_leituras.php" class="text-white text-decoration-none">Acompanhar <i class="bi bi-chevron-right"></i></a>
        </div>
      </div>
    </div>

    <div class="col-md-3">
      <div class="card text-white bg-danger shadow-sm">
        <div class="card-body">
          <h5 class="card-title">Mensagens</h5>
          <p class="card-text fs-3"><?= $total_msgs ?></p>
        </div>
        <div class="card-footer bg-transparent border-top-0">
          <a href="mensagens.php" class="text-white text-decoration-none">Ver mensagens <i class="bi bi-chevron-right"></i></a>
        </div>
      </div>
    </div>
  </div>

  <hr class="my-5">

  <div class="text-center">
  <a href="<?= URL_BASE ?>/index.php" class="btn btn-outline-secondary">
    🔙 Voltar ao site
  </a>
</div>


<?php require_once BASE_PATH . '/includes/footer.php'; ?>
</body>
</html>
