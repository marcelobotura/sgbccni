<?php
define('BASE_PATH', dirname(__DIR__) . '/../app_backend');
require_once BASE_PATH . '/config/config.php';
require_once BASE_PATH . '/includes/session.php';
include_once BASE_PATH . '/includes/header.php';

exigir_login('usuario');
$usuario_id = $_SESSION['usuario_id'];

// Buscar livros do usuário
$resultado = $conn->query("SELECT titulo, autor, status FROM livros_usuarios WHERE usuario_id = $usuario_id");

?>

<div class="container py-4">
  <h2>📚 Meus Livros</h2>
  <table class="table table-striped">
    <thead>
      <tr>
        <th>Título</th>
        <th>Autor</th>
        <th>Status</th>
      </tr>
    </thead>
    <tbody>
      <?php while($livro = $resultado->fetch_assoc()): ?>
        <tr>
          <td><?= htmlspecialchars($livro['titulo']) ?></td>
          <td><?= htmlspecialchars($livro['autor']) ?></td>
          <td><?= htmlspecialchars($livro['status']) ?></td>
        </tr>
      <?php endwhile; ?>
    </tbody>
  </table>
</div>

<?php include_once BASE_PATH . '/includes/footer.php'; ?>
