<?php
require_once __DIR__ . '/../../../backend/includes/protect_admin.php';
require_once __DIR__ . '/../../../backend/config/config.php';
require_once __DIR__ . '/../../../backend/includes/header.php';
require_once __DIR__ . '/../../../backend/includes/menu.php';

exigir_login('admin');

// Validar ID
$id = intval($_GET['id'] ?? 0);
if ($id <= 0) {
  echo "<div class='alert alert-danger'>ID inválido.</div>";
  exit;
}

// Buscar o livro
$stmt = $conn->prepare("SELECT * FROM livros WHERE id = :id");
$stmt->bindParam(':id', $id, PDO::PARAM_INT);
$stmt->execute();
$livro = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$livro) {
  echo "<div class='alert alert-warning'>Livro não encontrado.</div>";
  exit;
}
?>

<div class="container py-4">
  <h2>📖 Detalhes do Livro</h2>
  <table class="table table-bordered mt-3">
    <tr><th>ID</th><td><?= $livro['id'] ?></td></tr>
    <tr><th>Título</th><td><?= htmlspecialchars($livro['titulo']) ?></td></tr>
    <tr><th>ISBN</th><td><?= htmlspecialchars($livro['isbn']) ?></td></tr>
    <tr><th>Tipo</th><td><?= htmlspecialchars($livro['tipo']) ?></td></tr>
    <tr><th>Formato</th><td><?= htmlspecialchars($livro['formato']) ?></td></tr>
    <tr><th>Volume</th><td><?= htmlspecialchars($livro['volume']) ?></td></tr>
    <tr><th>Edição</th><td><?= htmlspecialchars($livro['edicao']) ?></td></tr>
    <tr><th>Código Interno</th><td><?= htmlspecialchars($livro['codigo_interno']) ?></td></tr>
    <tr><th>Descrição</th><td><?= nl2br(htmlspecialchars($livro['descricao'])) ?></td></tr>
    <tr><th>Link Digital</th><td>
      <?php if ($livro['link_digital']): ?>
        <a href="<?= htmlspecialchars($livro['link_digital']) ?>" target="_blank">Abrir Link</a>
      <?php else: ?>
        <em>Nenhum</em>
      <?php endif; ?>
    </td></tr>
  </table>

  <div class="text-center">
    <a href="<?= URL_BASE ?>frontend/admin/pages/listar_livros.php" class="btn btn-secondary">🔙 Voltar</a>
  </div>
</div>

<?php require_once __DIR__ . '/../../../backend/includes/footer.php'; ?>
