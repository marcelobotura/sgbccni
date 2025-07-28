<?php
// Caminho: frontend/usuario/log_visualizacoes.php

declare(strict_types=1);

define('BASE_PATH', dirname(__DIR__, 2));
require_once BASE_PATH . '/backend/config/config.php';
require_once BASE_PATH . '/backend/includes/session.php';

if (!isset($_SESSION['usuario_id'])) {
    $_SESSION['erro'] = "Você precisa estar logado.";
    header("Location: " . URL_BASE . "frontend/login/login.php");
    exit;
}

$usuario_id = $_SESSION['usuario_id'];

// 🔍 Buscar visualizações do usuário
try {
    $stmt = $pdo->prepare("
        SELECT lv.id, lv.data_visualizacao, l.titulo
        FROM log_visualizacoes lv
        JOIN livros l ON lv.livro_id = l.id
        WHERE lv.usuario_id = ?
        ORDER BY lv.data_visualizacao DESC
    ");
    $stmt->execute([$usuario_id]);
    $visualizacoes = $stmt->fetchAll();
} catch (PDOException $e) {
    $_SESSION['erro'] = "Erro ao buscar visualizações: " . $e->getMessage();
    $visualizacoes = [];
}
?>

<!DOCTYPE html>
<html lang="pt-br" data-theme="<?= htmlspecialchars($_COOKIE['tema'] ?? 'light') ?>">
<head>
  <meta charset="UTF-8">
  <title>Visualizações Recentes - <?= NOME_SISTEMA ?></title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

  <link rel="stylesheet" href="<?= URL_BASE ?>frontend/assets/css/base.css">
  <link rel="stylesheet" href="<?= URL_BASE ?>frontend/assets/css/layout.css">
  <link rel="stylesheet" href="<?= URL_BASE ?>frontend/assets/css/components.css">
  <link rel="stylesheet" href="<?= URL_BASE ?>frontend/assets/css/themes/user.css">
</head>
<body>

<main class="container py-4">
  <h2 class="mb-4"><i class="bi bi-eye"></i> Visualizações Recentes</h2>

  <?php if (isset($_SESSION['erro'])): ?>
    <div class="alert alert-danger"><?= $_SESSION['erro']; unset($_SESSION['erro']); ?></div>
  <?php endif; ?>

  <?php if (empty($visualizacoes)): ?>
    <div class="alert alert-info">Nenhuma visualização registrada.</div>
  <?php else: ?>
    <div class="table-responsive">
      <table class="table table-striped table-bordered align-middle">
        <thead class="table-light">
          <tr>
            <th>#</th>
            <th>Título do Livro</th>
            <th>Data da Visualização</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($visualizacoes as $v): ?>
            <tr>
              <td><?= $v['id'] ?></td>
              <td><?= htmlspecialchars($v['titulo']) ?></td>
              <td><?= date('d/m/Y H:i', strtotime($v['data_visualizacao'])) ?></td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  <?php endif; ?>
</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
