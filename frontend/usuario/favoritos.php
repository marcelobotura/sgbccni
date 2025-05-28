<?php
// Caminho seguro
define('BASE_PATH', realpath(__DIR__ . '/../../backend'));
require_once BASE_PATH . '/config/config.php';
require_once BASE_PATH . '/includes/session.php';

// Header correto (provavelmente em frontend/includes/)
include_once __DIR__ . '/../includes/header.php';

exigir_login('usuario');

$id = $_SESSION['usuario_id'] ?? 0;

// Verifica ID válido
if ($id <= 0) {
    echo "<div class='alert alert-danger'>Usuário inválido.</div>";
    include_once __DIR__ . '/../includes/footer.php';
    exit;
}

// Consulta segura com prepared statement
$stmt = $conn->prepare("SELECT titulo, autor FROM livros_usuarios WHERE usuario_id = ? AND favorito = 1");
$stmt->bind_param("i", $id);
$stmt->execute();
$res = $stmt->get_result();
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

<?php include_once __DIR__ . '/../includes/footer.php'; ?>
