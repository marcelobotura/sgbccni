<?php
// Caminho: frontend/usuario/notificacoes.php

declare(strict_types=1);
define('BASE_PATH', dirname(__DIR__, 2));
require_once BASE_PATH . '/backend/config/config.php';
require_once BASE_PATH . '/backend/includes/session.php';
require_once BASE_PATH . '/backend/includes/header.php';
require_once __DIR__ . '/protect_usuario.php';

exigir_login('usuario');
$usuario_id = $_SESSION['usuario_id'];

// 🔍 Buscar notificações
try {
    $stmt = $pdo->prepare("
        SELECT id, titulo, mensagem, tipo, prioridade, origem, lida, data, lida_em
        FROM notificacoes
        WHERE usuario_id = ?
        ORDER BY lida ASC, data DESC
    ");
    $stmt->execute([$usuario_id]);
    $notificacoes = $stmt->fetchAll();
} catch (PDOException $e) {
    $_SESSION['erro'] = "Erro ao buscar notificações: " . $e->getMessage();
    $notificacoes = [];
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <title>Notificações - <?= NOME_SISTEMA ?></title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <link rel="stylesheet" href="<?= URL_BASE ?>frontend/assets/css/base.css">
</head>
<body>

<div class="container py-4">
  <h2 class="mb-4"><i class="bi bi-bell"></i> Minhas Notificações</h2>

  <?php if (isset($_SESSION['erro'])): ?>
    <div class="alert alert-danger"><?= $_SESSION['erro']; unset($_SESSION['erro']); ?></div>
  <?php endif; ?>

  <?php if (empty($notificacoes)): ?>
    <div class="alert alert-info">Nenhuma notificação encontrada.</div>
  <?php else: ?>
    <div class="list-group">
      <?php foreach ($notificacoes as $n): ?>
        <?php
          $tipoCor = match($n['tipo']) {
              'sucesso' => 'success',
              'alerta' => 'warning',
              'erro' => 'danger',
              default => 'secondary'
          };
          $icone = match($n['tipo']) {
              'sucesso' => 'bi-check-circle-fill',
              'alerta' => 'bi-exclamation-triangle-fill',
              'erro' => 'bi-x-circle-fill',
              default => 'bi-info-circle'
          };
          $classeLida = $n['lida'] ? 'opacity-75' : 'fw-bold';
        ?>
        <div class="list-group-item list-group-item-action border-<?= $tipoCor ?> <?= $classeLida ?>">
          <div class="d-flex justify-content-between align-items-center">
            <h5 class="mb-1">
              <i class="bi <?= $icone ?>"></i> <?= htmlspecialchars($n['titulo']) ?>
              <?php if ($n['prioridade']): ?>
                <span class="badge bg-danger ms-2">Prioritária</span>
              <?php endif; ?>
            </h5>
            <small class="text-muted"><?= date('d/m/Y H:i', strtotime($n['data'])) ?></small>
          </div>
          <p class="mb-1"><?= nl2br(htmlspecialchars($n['mensagem'])) ?></p>
          <small class="text-muted">Origem: <?= htmlspecialchars($n['origem'] ?? 'Sistema') ?></small>
        </div>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
