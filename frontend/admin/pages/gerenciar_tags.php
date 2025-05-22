<?php
require_once __DIR__ . '/../../../backend/includes/header.php';
require_once __DIR__ . '/../../../backend/includes/menu.php';
require_once __DIR__ . '/../../../backend/config/config.php';
exigir_login('admin');

$tipos = ['autor', 'categoria', 'editora', 'outro'];
$tags = [];
foreach ($tipos as $tipo) {
    $stmt = $conn->prepare("SELECT id, nome FROM tags WHERE tipo = ? ORDER BY nome ASC");
    $stmt->bind_param("s", $tipo);
    $stmt->execute();
    $result = $stmt->get_result();
    $tags[$tipo] = $result->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
}
?>

<div class="container py-4">
  <h2 class="mb-4">🏷️ Gerenciar Tags</h2>

  <?php if (!empty($_SESSION['sucesso'])): ?>
    <div class="alert alert-success"><?= htmlspecialchars($_SESSION['sucesso']); unset($_SESSION['sucesso']); ?></div>
  <?php elseif (!empty($_SESSION['erro'])): ?>
    <div class="alert alert-danger"><?= htmlspecialchars($_SESSION['erro']); unset($_SESSION['erro']); ?></div>
  <?php endif; ?>

  <?php foreach ($tags as $tipo => $lista): ?>
    <div class="mb-4">
      <h5 class="text-capitalize"><?= ucfirst($tipo) ?>s</h5>
      <table class="table table-bordered table-striped">
        <thead>
          <tr>
            <th>#</th>
            <th>Nome</th>
            <th>Ações</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($lista as $tag): ?>
            <tr>
              <td><?= $tag['id'] ?></td>
              <td><?= htmlspecialchars($tag['nome']) ?></td>
              <td>
                <!-- Botões futuros: editar, excluir -->
                <button class="btn btn-sm btn-secondary disabled" disabled>Editar</button>
                <button class="btn btn-sm btn-danger disabled" disabled>Excluir</button>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  <?php endforeach; ?>
</div>

<?php require_once __DIR__ . '/../../../backend/includes/footer.php'; ?>