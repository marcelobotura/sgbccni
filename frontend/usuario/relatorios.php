<?php
define('BASE_PATH', dirname(__DIR__, 2) . '/backend');
require_once BASE_PATH . '/config/config.php';
require_once BASE_PATH . '/includes/session.php';
require_once BASE_PATH . '/includes/header.php';
;

exigir_login('usuario');

// Simulação de dados (futuramente virá do banco de dados)
$usuario = htmlspecialchars($_SESSION['usuario_nome'] ?? 'Usuário');
$total_lidos = 12;
$em_andamento = 2;
$favoritos = 5;
$ultimo_lido = "O Pequeno Príncipe";
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <title>Relatórios - Biblioteca CNI</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- Estilos e ícones -->
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
  <link rel="stylesheet" href="<?= URL_BASE ?>frontend/assets/css/base.css">
  <link rel="stylesheet" href="<?= URL_BASE ?>frontend/assets/css/layout.css">
  <link rel="stylesheet" href="<?= URL_BASE ?>frontend/assets/css/components.css">
  <link rel="stylesheet" href="<?= URL_BASE ?>frontend/assets/css/themes/light.css" id="theme-style">
</head>
<body>

<div class="container py-5">
  <div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="bi bi-bar-chart-fill"></i> Relatórios de Leitura</h2>
    <a href="index.php" class="btn btn-sm btn-outline-secondary">← Voltar ao painel</a>
  </div>

  <div class="row g-4">
    <div class="col-md-6 col-xl-3">
      <div class="card text-center shadow-sm">
        <div class="card-body">
          <h5 class="card-title">📚 Livros Lidos</h5>
          <p class="display-5 text-primary"><?= $total_lidos ?></p>
        </div>
      </div>
    </div>

    <div class="col-md-6 col-xl-3">
      <div class="card text-center shadow-sm">
        <div class="card-body">
          <h5 class="card-title">⏳ Em Andamento</h5>
          <p class="display-5 text-warning"><?= $em_andamento ?></p>
        </div>
      </div>
    </div>

    <div class="col-md-6 col-xl-3">
      <div class="card text-center shadow-sm">
        <div class="card-body">
          <h5 class="card-title">⭐ Favoritos</h5>
          <p class="display-5 text-success"><?= $favoritos ?></p>
        </div>
      </div>
    </div>

    <div class="col-md-6 col-xl-3">
      <div class="card text-center shadow-sm">
        <div class="card-body">
          <h5 class="card-title">📖 Último Lido</h5>
          <p class="fs-5 text-dark"><?= $ultimo_lido ?></p>
        </div>
      </div>
    </div>
  </div>

  <div class="mt-5">
    <h4 class="mb-3">Resumo para <?= $usuario ?>:</h4>
    <ul class="list-group">
      <li class="list-group-item">Total de livros lidos: <strong><?= $total_lidos ?></strong></li>
      <li class="list-group-item">Atualmente lendo: <strong><?= $em_andamento ?></strong></li>
      <li class="list-group-item">Livros favoritos: <strong><?= $favoritos ?></strong></li>
      <li class="list-group-item">Último livro lido: <strong><?= $ultimo_lido ?></strong></li>
    </ul>
  </div>
</div>
<?php require_once BASE_PATH . '/includes/footer.php'; ?>
</body>
</html>
