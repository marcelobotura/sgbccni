<?php
session_start();
define('BASE_PATH', dirname(__DIR__) . '/backend');
require_once BASE_PATH . '/config/config.php';
require_once BASE_PATH . '/includes/session.php';
require_once BASE_PATH . '/includes/header.php';
require_once __DIR__ . 'protect_admin.php';

exigir_login('admin');

$sql = "SELECT id, nome, email, tipo FROM usuarios ORDER BY tipo DESC, nome ASC";
$resultado = $conn->query($sql);
?>

<div class="container py-4">
  <div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="mb-0">👥 Usuários do Sistema</h3>
    <a href="register_admin.php" class="btn btn-success">➕ Novo Admin</a>
  </div>

  <?php if (!empty($_SESSION['sucesso'])): ?>
    <div class="alert alert-success"><?= htmlspecialchars($_SESSION['sucesso']); unset($_SESSION['sucesso']); ?></div>
  <?php elseif (!empty($_SESSION['erro'])): ?>
    <div class="alert alert-danger"><?= htmlspecialchars($_SESSION['erro']); unset($_SESSION['erro']); ?></div>
  <?php endif; ?>

  <?php if ($resultado->num_rows === 0): ?>
    <div class="alert alert-warning text-center">Nenhum usuário cadastrado ainda.</div>
  <?php else: ?>
    <div class="table-responsive">
      <table class="table table-bordered table-striped align-middle">
        <thead class="table-dark">
          <tr>
            <th>ID</th>
            <th>Nome</th>
            <th>E-mail</th>
            <th>Tipo</th>
            <th class="text-center">Ações</th>
          </tr>
        </thead>
        <tbody>
          <?php while ($usuario = $resultado->fetch_assoc()): ?>
            <tr>
              <td><?= $usuario['id'] ?></td>
              <td><?= htmlspecialchars($usuario['nome']) ?></td>
              <td><?= htmlspecialchars($usuario['email']) ?></td>
              <td>
                <span class="badge bg-<?= $usuario['tipo'] === 'admin' ? 'dark' : 'secondary' ?>">
                  <?= ucfirst($usuario['tipo']) ?>
                </span>
              </td>
              <td class="text-center">
                <a href="editar_usuario.php?id=<?= $usuario['id'] ?>" class="btn btn-sm btn-warning">✏️ Editar</a>
                <?php if ($_SESSION['usuario_id'] != $usuario['id']): ?>
                  <a href="excluir_usuario.php?id=<?= $usuario['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Tem certeza que deseja excluir este usuário?')">🗑️ Excluir</a>
                <?php else: ?>
                  <span class="text-muted">🔒</span>
                <?php endif; ?>
              </td>
            </tr>
          <?php endwhile; ?>
        </tbody>
      </table>
    </div>
  <?php endif; ?>
</div>

<?php require_once BASE_PATH . '/includes/footer.php'; ?>
