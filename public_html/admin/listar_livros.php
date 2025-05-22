<?php
define('BASE_PATH', dirname(__DIR__) . '/../app_backend');
require_once BASE_PATH . '/config/config.php';
require_once BASE_PATH . '/includes/session.php';
include_once BASE_PATH . '/includes/header.php';

exigir_login('admin');

$filtro = $_GET['filtro'] ?? '';
$sql = "SELECT l.*, 
           (SELECT nome FROM tags WHERE id = l.autor_id) AS autor,
           (SELECT nome FROM tags WHERE id = l.editora_id) AS editora,
           (SELECT nome FROM tags WHERE id = l.categoria_id) AS categoria
        FROM livros l
        WHERE l.titulo LIKE CONCAT('%', ?, '%')
        ORDER BY l.criado_em DESC";

$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $filtro);
$stmt->execute();
$result = $stmt->get_result();
?>

<div class="container py-4">
  <div class="d-flex justify-content-between align-items-center mb-4">
    <h2>📚 Livros Cadastrados</h2>
    <form class="d-flex" method="GET">
      <input type="text" name="filtro" class="form-control me-2" placeholder="Buscar por título" value="<?= htmlspecialchars($filtro) ?>">
      <button class="btn btn-primary">🔍 Buscar</button>
    </form>
  </div>

  <?php if ($result->num_rows): ?>
    <div class="table-responsive">
      <table class="table table-bordered table-hover align-middle">
        <thead class="table-light">
          <tr>
            <th>Capa</th>
            <th>Título</th>
            <th>Autor</th>
            <th>Editora</th>
            <th>Categoria</th>
            <th>Formato</th>
            <th>Ações</th>
          </tr>
        </thead>
        <tbody>
        <?php while($livro = $result->fetch_assoc()): ?>
          <tr>
            <td>
              <?php if ($livro['capa_url']): ?>
                <img src="<?= $livro['capa_url'] ?>" alt="Capa" style="height: 80px;">
              <?php endif; ?>
            </td>
            <td><?= htmlspecialchars($livro['titulo']) ?></td>
            <td><?= htmlspecialchars($livro['autor']) ?></td>
            <td><?= htmlspecialchars($livro['editora']) ?></td>
            <td><?= htmlspecialchars($livro['categoria']) ?></td>
            <td><?= htmlspecialchars($livro['formato']) ?></td>
            <td>
              <a href="../livro.php?isbn=<?= urlencode($livro['isbn']) ?>" class="btn btn-sm btn-outline-primary" target="_blank">🔍 Ver</a>
              <a href="editar_livro.php?id=<?= $livro['id'] ?>" class="btn btn-sm btn-outline-warning">✏️ Editar</a>
              <a href="excluir_livro.php?id=<?= $livro['id'] ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Deseja realmente excluir?')">🗑️ Excluir</a>
              <?php if ($livro['qr_code']): ?>
                <a href="<?= $livro['qr_code'] ?>" target="_blank" class="btn btn-sm btn-outline-dark">📱 QR</a>
              <?php endif; ?>
            </td>
          </tr>
        <?php endwhile; ?>
        </tbody>
      </table>
    </div>
  <?php else: ?>
    <div class="alert alert-info">Nenhum livro encontrado.</div>
  <?php endif; ?>
</div>

<?php include_once BASE_PATH . '/includes/footer.php'; ?>
