<?php
// 🔧 Exibir erros para desenvolvimento (remova em produção)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// ✅ Caminho base
define('BASE_PATH', dirname(__DIR__, 2));
require_once BASE_PATH . '/backend/config/config.php';
require_once BASE_PATH . '/backend/includes/session.php';
require_once BASE_PATH . '/backend/includes/header.php';

// 🔒 Restringe acesso
exigir_login('usuario');

// 📚 Buscar histórico de leitura
$usuario_id = $_SESSION['usuario_id'];

$sql = "SELECT l.id, l.titulo, 
               (SELECT nome FROM tags WHERE id = l.autor_id) AS autor,
               l.capa, lu.status, lu.data_leitura
        FROM livros_usuarios lu
        JOIN livros l ON lu.livro_id = l.id
        WHERE lu.usuario_id = :usuario_id
        ORDER BY lu.data_leitura DESC";

$stmt = $pdo->prepare($sql);
$stmt->execute([':usuario_id' => $usuario_id]);
$livros = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="container py-4">
  <h3 class="mb-4">📖 Histórico de Leitura</h3>

  <?php if (count($livros) === 0): ?>
    <div class="alert alert-secondary text-center">Nenhum livro registrado ainda.</div>
  <?php else: ?>
    <div class="table-responsive">
      <table class="table table-hover table-bordered align-middle">
        <thead class="table-dark">
          <tr>
            <th style="width: 80px;">Capa</th>
            <th>Título</th>
            <th>Autor</th>
            <th>Status</th>
            <th>Data de Leitura</th>
            <th style="width: 180px;">Ações</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($livros as $livro): ?>
            <tr>
              <td>
                <img src="<?= 
                  !empty($livro['capa']) 
                    ? URL_BASE . 'uploads/capas/' . htmlspecialchars($livro['capa']) 
                    : URL_BASE . 'assets/img/sem_capa.png' ?>" 
                  alt="Capa" class="img-thumbnail" style="height: 60px; object-fit: cover;">
              </td>
              <td><?= htmlspecialchars($livro['titulo']) ?></td>
              <td><?= htmlspecialchars($livro['autor'] ?? 'Autor desconhecido') ?></td>
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
                <?php if ($livro['status'] !== 'lido'): ?>
                  <form method="POST" action="<?= URL_BASE ?>usuario/marcar_lido.php" class="d-inline">
                    <input type="hidden" name="livro_id" value="<?= $livro['id'] ?>">
                    <button type="submit" class="btn btn-sm btn-success">✔️ Marcar como lido</button>
                  </form>
                <?php endif; ?>

                <form method="POST" action="<?= URL_BASE ?>usuario/remover_livro_usuario.php" class="d-inline" onsubmit="return confirm('Tem certeza que deseja remover este livro do histórico?');">
                  <input type="hidden" name="livro_id" value="<?= $livro['id'] ?>">
                  <button type="submit" class="btn btn-sm btn-outline-danger">🗑️ Remover</button>
                </form>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  <?php endif; ?>
</div>

<?php require_once BASE_PATH . '/backend/includes/footer.php'; ?>
