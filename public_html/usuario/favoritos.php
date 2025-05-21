<?php
define('BASE_PATH', dirname(__DIR__) . '/../app_backend');
require_once BASE_PATH . '/config/config.php';
require_once BASE_PATH . '/includes/session.php';
include_once BASE_PATH . '/includes/header.php';

exigir_login('usuario');
$id = $_SESSION['usuario_id'];

$res = $conn->query("SELECT titulo, autor FROM livros_usuarios WHERE usuario_id = $id AND favorito = 1");
?>

<div class="container py-4">
  <h2>⭐ Meus Favoritos</h2>
  <?php if ($res->num_rows): ?>
    <ul class="list-group">
      <?php while($l = $res->fetch_assoc()): ?>
        <li class="list-group-item"><?= htmlspecialchars($l['titulo']) ?> — <em><?= htmlspecialchars($l['autor']) ?></em></li>
      <?php endwhile; ?>
    </ul>
  <?php else: ?>
    <p class="text-muted">Você ainda não marcou livros como favoritos.</p>
  <?php endif; ?>
</div>

<?php include_once BASE_PATH . '/includes/footer.php'; ?>
