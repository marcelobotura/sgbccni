<?php
define('BASE_PATH', dirname(__DIR__) . '/backend');
require_once BASE_PATH . '/config/config.php';
require_once BASE_PATH . '/includes/session.php';
require_once BASE_PATH . '/includes/header.php';

exigir_login('usuario');

$usuario_id = $_SESSION['usuario_id'];

// Consulta todos os livros lidos ou registrados pelo usuário
$sql = "SELECT l.id, l.titulo, l.autor, l.capa, lu.status, lu.data_leitura
        FROM livros_usuarios lu
        JOIN livros l ON lu.livro_id = l.id
        WHERE lu.usuario_id = ?
        ORDER BY lu.data_leitura DESC";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $usuario_id);
$stmt->execute();
$resultado = $stmt->get_result();
?>

<div class="container py-4">
  <h3 class="mb-4">📖 Histórico de Leitura</h3>

  <?php if ($resultado->num_rows === 0): ?>
    <div class="alert alert-secondary text-center">Nenhum livro registrado ainda.</div>
  <?php else: ?>
    <div class="table-responsive">
      <table class="table table-hover table-bordered align-middle">
        <thead class="table-dark">
          <tr>
            <th>Capa</th>
            <th>Título</th>
            <th>Autor</th>
            <th>Status</th>
            <th>Data de Leitura</th>
          </tr>
        </thead>
        <tbody>
          <?php while ($livro = $resultado->fetch_assoc()): ?>
            <tr>
              <td style="width: 80px;">
                <img src="<?= URL_BASE . $livro['capa'] ?>" alt="Capa" class="img-thumbnail" style="height: 60px; object-fit: cover;">
              </td>
              <td><?= htmlspecialchars($livro['titulo']) ?></td>
              <td><?= htmlspecialchars($livro['autor']) ?></td>
              <td>
                <?php
                  $badgeClass = match ($livro['status']) {
                    'lido' => 'success',
                    'em_leitura' => 'primary',
                    'favorito' => 'warning',
                    default => 'secondary'
                  };
                ?>
                <span class="badge bg-<?= $badgeClass ?>"><?= ucfirst($livro['status']) ?></span>
              </td>
              <td><?= $livro['data_leitura'] ? date('d/m/Y', strtotime($livro['data_leitura'])) : '-' ?></td>
            </tr>
          <?php endwhile; ?>
        </tbody>
      </table>
    </div>
  <?php endif; ?>
</div>

<?php require_once BASE_PATH . '/includes/footer.php'; ?>
