<?php
define('BASE_PATH', __DIR__ . '/../app_backend');
require_once BASE_PATH . '/config/config.php';
require_once BASE_PATH . '/includes/session.php';
include_once BASE_PATH . '/includes/header.php';

// Verifica se usuário já está logado
if (isset($_SESSION['usuario_id'])) {
  if ($_SESSION['usuario_tipo'] === 'admin') {
    header("Location: login/../admin/listar_livros.php");
    exit;
  } else {
    header("Location: login/../usuario/index.php");
    exit;
  }
}
?>

<div class="container py-5 text-center">
  <h1 class="display-5 fw-bold">Bem-vindo à Biblioteca Comunitária CNI</h1>
  <p class="lead">Faça login ou explore o sistema.</p>
  <div class="d-flex justify-content-center gap-3 mt-4">
    <a href="login/login.php" class="btn btn-primary">🔐 Login</a>
    <a href="login/register.php" class="btn btn-outline-success">📝 Criar Conta</a>
  </div>
</div>

<?php include_once BASE_PATH . '/includes/footer.php'; ?>
