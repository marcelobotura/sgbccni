<?php
require_once __DIR__ . '/../../../backend/includes/verifica_admin.php';
require_once __DIR__ . '/../../../backend/includes/header.php';
require_once __DIR__ . '/../../../backend/includes/menu.php';
require_once __DIR__ . '/../../../backend/config/config.php';
exigir_login('admin');

// 🔍 Busca de tags por tipo
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

  <!-- ✅ Alertas -->
  <?php if (!empty($_SESSION['sucesso'])): ?>
    <div class="alert alert-success"><?= htmlspecialchars($_SESSION['sucesso']); unset($_SESSION['sucesso']); ?></div>
  <?php elseif (!empty($_SESSION['erro'])): ?>
    <div class="alert alert-danger"><?= htmlspecialchars($_SESSION['erro']); unset($_SESSION['erro']); ?></div>
  <?php endif; ?>

  <!-- 🧾 Tabelas de tags por tipo -->
  <?php foreach ($tags as $tipo => $lista): ?>
    <div class="mb-5">
      <h5 class="text-capitalize"><?= ucfirst($tipo) ?>s</h5>
      <div class="table-responsive">
        <table class="table table-bordered table-striped align-middle">
          <thead class="table-dark">
            <tr>
              <th style="width: 60px;">#</th>
              <th>Nome</th>
              <th style="width: 150px;">Ações</th>
            </tr>
          </thead>
          <tbody>
            <?php if (count($lista) === 0): ?>
              <tr><td colspan="3" class="text-muted text-center">Nenhum item encontrado.</td></tr>
            <?php else: ?>
              <?php foreach ($lista as $tag): ?>
                <tr>
                  <td><?= $tag['id'] ?></td>
                  <td><?= htmlspecialchars($tag['nome']) ?></td>
                  <td>
                    <button class="btn btn-sm btn-secondary disabled" disabled>Editar</button>
                    <button class="btn btn-sm btn-danger disabled" disabled>Excluir</button>
                  </td>
                </tr>
              <?php endforeach; ?>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>
  <?php endforeach; ?>
</div>

<?php require_once __DIR__ . '/../../../backend/includes/footer.php'; ?>
