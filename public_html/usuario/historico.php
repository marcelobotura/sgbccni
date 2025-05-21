<?php
define('BASE_PATH', dirname(__DIR__) . '/../app_backend');
require_once BASE_PATH . '/config/config.php';
require_once BASE_PATH . '/includes/session.php';
include_once BASE_PATH . '/includes/header.php';

exigir_login('usuario');
$id = $_SESSION['usuario_id'];

$historico = $conn->query("SELECT titulo, data_leitura FROM livros_usuarios WHERE usuario_id = $id AND status = 'concluído'");
?>

<div class="container py-4">
  <h2>🕓 Histórico de Leitura</h2>
  <?php if ($historico->num_rows): ?>
    <table class="table table-hover">
      <thead><tr><th>Título</th><th>Data de Leitura</th></tr></thead>
      <tbody>
        <?php while($h = $historico->fetch_assoc()): ?>
          <tr>
            <td><?= htmlspecialchars($h['titulo']) ?></td>
            <td><?= htmlspecialchars($h['data_leitura']) ?></td>
          </tr>
        <?php endwhile; ?>
      </tbody>
    </table>
  <?php else: ?>
    <p class="text-muted">Você ainda não concluiu nenhuma leitura.</p>
  <?php endif; ?>
</div>

<?php include_once BASE_PATH . '/includes/footer.php'; ?>
