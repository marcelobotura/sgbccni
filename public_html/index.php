<?php 
define('BASE_PATH', __DIR__ . '/../app_backend');
require_once BASE_PATH . '/config/config.php';
require_once BASE_PATH . '/includes/session.php';
include_once BASE_PATH . '/includes/header.php';

// Redirecionamento automático se já estiver logado
if (isset($_SESSION['usuario_id'])) {
  if ($_SESSION['usuario_tipo'] === 'admin') {
    header("Location: ../admin/listar_livros.php");
    exit;
  } else {
    header("Location: ../usuario/index.php");
    exit;
  }
}

?>

<div class="container py-5 text-center">
  <h1 class="display-5 fw-bold">Bem-vindo à Biblioteca Comunitária CNI</h1>
  <p class="lead">Faça login ou explore o sistema.</p>
  <div class="d-flex justify-content-center gap-3 mt-4">
    <a href="index.php" class="btn btn-primary">🔐 Login</a>
    <a href="register.php" class="btn btn-outline-success">📝 Criar Conta</a>
  </div>
</div>

<?php include_once BASE_PATH . '/includes/footer.php'; ?>
