<?php
require_once dirname(__DIR__, 2) . '/backend/config/config.php';
require_once dirname(__DIR__, 2) . '/backend/includes/session.php';
include_once dirname(__DIR__, 2) . '/backend/includes/header.php';

exigir_login('admin');

// Busca todos os livros
$sql = "SELECT l.*, t1.nome AS autor, t2.nome AS editora, t3.nome AS categoria
        FROM livros l
        LEFT JOIN tags t1 ON l.autor_id = t1.id
        LEFT JOIN tags t2 ON l.editora_id = t2.id
        LEFT JOIN tags t3 ON l.categoria_id = t3.id
        ORDER BY l.id DESC";
$result = $conn->query($sql);
?>

<div class="container py-4">
  <h2 class="mb-4">📚 Livros Cadastrados</h2>
  <table class="table table-striped">
    <thead>
      <tr>
        <th>ID</th>
        <th>Título</th>
        <th>Autor</th>
        <th>Editora</th>
        <th>Categoria</th>
        <th>Status</th>
        <th>Ações</th>
      </tr>
    </thead>
    <tbody>
      <?php while ($livro = $result->fetch_assoc()): ?>
      <tr>
        <td><?= $livro['id'] ?></td>
        <td><?= htmlspecialchars($livro['titulo']) ?></td>
        <td><?= htmlspecialchars($livro['autor']) ?></td>
        <td><?= htmlspecialchars($livro['editora']) ?></td>
        <td><?= htmlspecialchars($livro['categoria']) ?></td>
        <td><span class="badge bg-<?= $livro['status'] === 'disponivel' ? 'success' : 'danger' ?>">
          <?= ucfirst($livro['status']) ?></span>
        </td>
        <td>
          <a href="editar_livro.php?id=<?= $livro['id'] ?>" class="btn btn-sm btn-warning">✏️</a>
          <a href="gerar_qrcode.php?id=<?= $livro['id'] ?>" class="btn btn-sm btn-dark">QR</a>
          <a href="excluir_livro.php?id=<?= $livro['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Tem certeza?')">🗑️</a>
        </td>
      </tr>
      <?php endwhile; ?>
    </tbody>
  </table>
</div>

<?php include_once dirname(__DIR__, 2) . '/backend/includes/footer.php'; ?>