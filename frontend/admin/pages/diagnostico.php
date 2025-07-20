<?php
// Caminho: frontend/admin/pages/diagnostico.php

define('BASE_PATH', dirname(__DIR__, 3)); // Vai até /sgbccni
require_once BASE_PATH . '/backend/config/config.php';
require_once BASE_PATH . '/backend/includes/protect_admin.php';
require_once BASE_PATH . '/backend/includes/header.php';
require_once BASE_PATH . '/backend/includes/menu.php';

exigir_login('admin');
?>

<div class="container py-4">
  <h2 class="mb-4"><i class="bi bi-gear-wide-connected"></i> Diagnóstico do Sistema</h2>

  <?php if (!empty($sucessos)): ?>
    <div class="alert alert-success">
      <ul class="mb-0">
        <?php foreach ($sucessos as $msg): ?>
          <li><?= htmlspecialchars($msg) ?></li>
        <?php endforeach; ?>
      </ul>
    </div>
  <?php endif; ?>

  <?php if (!empty($erros)): ?>
    <div class="alert alert-danger">
      <h5><i class="bi bi-exclamation-triangle-fill"></i> Erros encontrados:</h5>
      <ul class="mb-0">
        <?php foreach ($erros as $msg): ?>
          <li><?= htmlspecialchars($msg) ?></li>
        <?php endforeach; ?>
      </ul>
    </div>
  <?php endif; ?>

  <?php if (empty($erros) && empty($sucessos)): ?>
    <div class="alert alert-warning">Nenhum teste foi executado.</div>
  <?php endif; ?>

  <div class="mt-4">
    <a href="index.php" class="btn btn-outline-secondary"><i class="bi bi-arrow-left"></i> Voltar ao Painel</a>
  </div>
</div>

<?php require_once BASE_PATH . '/backend/includes/footer.php'; ?>
