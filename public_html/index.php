<?php
define('BASE_PATH', __DIR__ . '/../app_backend');
require_once BASE_PATH . '/config/config.php';
require_once BASE_PATH . '/includes/session.php';
include_once BASE_PATH . '/includes/header.php';

$logado = isset($_SESSION['usuario_id']);
$nome = $logado ? $_SESSION['usuario_nome'] : null;
?>

<div class="container py-5 text-center">
  <?php if ($logado): ?>
    <h1 class="display-5 fw-bold">👋 Olá, <?= htmlspecialchars($nome) ?>!</h1>
    <p class="lead">Acesse seus livros, favoritos ou perfil abaixo.</p>
    <div class="d-flex justify-content-center gap-3 mt-4">
      <a href="<?= URL_BASE ?>usuario/index.php" class="btn btn-primary">📚 Ir para Minha Área</a>
      <a href="<?= URL_BASE ?>usuario/perfil.php" class="btn btn-outline-secondary">👤 Meu Perfil</a>
    </div>
  <?php else: ?>
    <h1 class="display-5 fw-bold">Bem-vindo à Biblioteca Comunitária CNI</h1>
    <p class="lead">Faça login ou explore o sistema.</p>
    <div class="d-flex justify-content-center gap-3 mt-4">
      <a href="<?= URL_BASE ?>login/index.php" class="btn btn-primary">🔐 Login</a>
      <a href="<?= URL_BASE ?>login/register.php" class="btn btn-outline-success">📝 Criar Conta</a>
    </div>
  <?php endif; ?>
</div>

<?php include_once BASE_PATH . '/includes/footer.php'; ?>
