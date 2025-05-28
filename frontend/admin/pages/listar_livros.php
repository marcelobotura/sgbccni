<?php
require_once __DIR__ . '/../../../backend/includes/verifica_admin.php';
require_once __DIR__ . '/../../../backend/includes/header.php';
require_once __DIR__ . '/../../../backend/includes/menu.php';
require_once __DIR__ . '/../../../backend/config/config.php';
exigir_login('admin');

// 🔎 Consulta livros
$resultado = $conn->query("SELECT id, titulo, isbn, tipo, formato FROM livros ORDER BY id DESC");
?>

<div class="container py-4">
  <h2 class="mb-4">📚 Lista de Livros</h2>

  <?php if (!empty($_SESSION['sucesso'])): ?>
    <div class="alert alert-success"><?= htmlspecialchars($_SESSION['sucesso']); unset($_SESSION['sucesso']); ?></div>
  <?php elseif (!empty($_SESSION['erro'])): ?>
    <div class="alert alert-danger"><?= htmlspecialchars($_SESSION['erro']); unset($_SESSION['erro']); ?></div>
  <?php endif; ?>

  <table class="table table-bordered table-hover table-striped align-middle">
    <thead class="table-dark">
      <tr>
        <th>ID</th>
        <th>Título</th>
        <th>ISBN</th>
        <th>Tipo</th>
        <th>Formato</th>
        <th style="width: 220px;">Ações</th>
      </tr>
    </thead>
    <tbody>
      <?php while ($livro = $resultado->fetch_assoc()): ?>
        <tr>
          <td><?= $livro['id'] ?></td>
          <td><?= htmlspecialchars($livro['titulo']) ?></td>
          <td><?= htmlspecialchars($livro['isbn']) ?></td>
          <td><?= ucfirst(htmlspecialchars($livro['tipo'])) ?></td>
          <td><?= strtoupper(htmlspecialchars($livro['formato'])) ?></td>
          <td>
            <a href="<?= URL_BASE ?>frontend/admin/pages/editar_livro.php?id=<?= $livro['id'] ?>"
               class="btn btn-sm btn-primary" title="Editar Livro">
              <i class="bi bi-pencil"></i> Editar
            </a>
            <a href="<?= URL_BASE ?>backend/controllers/livros/excluir_livro.php?id=<?= $livro['id'] ?>"
               class="btn btn-sm btn-danger" title="Excluir Livro"
               onclick="return confirm('Tem certeza que deseja excluir este livro?');">
              <i class="bi bi-trash"></i> Excluir
            </a>
            <a href="<?= URL_BASE ?>livro.php?id=<?= $livro['id'] ?>" target="_blank"
               class="btn btn-sm btn-outline-secondary" title="Visualizar Livro">
              <i class="bi bi-eye"></i> Ver
            </a>
          </td>
        </tr>
      <?php endwhile; ?>
    </tbody>
  </table>
</div>

<?php require_once __DIR__ . '/../../../backend/includes/footer.php'; ?>
