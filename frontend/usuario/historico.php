<?php
define('BASE_PATH', dirname(__DIR__, 2) . '/backend');
require_once BASE_PATH . '/config/config.php';
require_once BASE_PATH . '/includes/session.php';
include_once __DIR__ . '/../includes/header.php';

exigir_login('usuario');

$usuario_id = $_SESSION['usuario_id'];

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
            <th>Ações</th>
          </tr>
        </thead>
        <tbody>
          <?php while ($livro = $resultado->fetch_assoc()): ?>
            <tr>
              <td style="width: 80px;">
                <img src="<?= !empty($livro['capa']) ? URL_BASE . 'uploads/capas/' . $livro['capa'] : URL_BASE . 'assets/img/placeholder.png' ?>" 
                     alt="Capa" class="img-thumbnail" style="height: 60px; object-fit: cover;">
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
              <td>
                <!-- Marcar como lido -->
                <?php if ($livro['status'] !== 'lido'): ?>
                  <form method="POST" action="<?= URL_BASE ?>usuario/marcar_lido.php" class="d-inline">
                    <input type="hidden" name="livro_id" value="<?= $livro['id'] ?>">
                    <button type="submit" class="btn btn-sm btn-success">Marcar como lido</button>
                  </form>
                <?php endif; ?>

                <!-- Remover do histórico -->
                <form method="POST" action="<?= URL_BASE ?>usuario/remover_livro_usuario.php" class="d-inline" onsubmit="return confirm('Tem certeza que deseja remover este livro do histórico?');">
                  <input type="hidden" name="livro_id" value="<?= $livro['id'] ?>">
                  <button type="submit" class="btn btn-sm btn-outline-danger">Remover</button>
                </form>
              </td>
            </tr>
          <?php endwhile; ?>
        </tbody>
      </table>
    </div>
  <?php endif; ?>
</div>

<?php include_once __DIR__ . '/../includes/footer.php'; ?>
