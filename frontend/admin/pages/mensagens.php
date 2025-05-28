<?php
session_start();
define('BASE_PATH', dirname(__DIR__) . '/backend');
require_once __DIR__ . '/../../../backend/includes/verifica_admin.php';
require_once BASE_PATH . '/config/config.php';
require_once BASE_PATH . '/includes/session.php';
require_once BASE_PATH . '/includes/header.php';

exigir_login('admin');

$resultado = $conn->query("SELECT * FROM mensagens_contato ORDER BY data_envio DESC");
?>

<div class="container py-4">
  <h3 class="mb-4">📩 Mensagens Recebidas</h3>

  <?php if ($resultado->num_rows === 0): ?>
    <div class="alert alert-info text-center">Nenhuma mensagem de contato foi recebida ainda.</div>
  <?php else: ?>
    <div class="table-responsive">
      <table class="table table-bordered table-hover align-middle">
        <thead class="table-dark">
          <tr>
            <th>Nome</th>
            <th>E-mail</th>
            <th style="width: 40%;">Mensagem</th>
            <th>Data</th>
            <th class="text-center">Ação</th>
          </tr>
        </thead>
        <tbody>
          <?php while ($msg = $resultado->fetch_assoc()): ?>
            <tr>
              <td><?= htmlspecialchars($msg['nome']) ?></td>
              <td>
                <a href="mailto:<?= htmlspecialchars($msg['email']) ?>" title="Enviar e-mail">
                  <i class="bi bi-envelope-fill"></i> <?= htmlspecialchars($msg['email']) ?>
                </a>
              </td>
              <td><?= nl2br(htmlspecialchars($msg['mensagem'])) ?></td>
              <td><?= date('d/m/Y H:i', strtotime($msg['data_envio'])) ?></td>
              <td class="text-center">
                <a href="excluir_mensagem.php?id=<?= $msg['id'] ?>"
                   class="btn btn-sm btn-danger"
                   onclick="return confirm('Excluir esta mensagem?')"
                   aria-label="Excluir mensagem">
                  <i class="bi bi-trash-fill"></i>
                </a>
              </td>
            </tr>
          <?php endwhile; ?>
        </tbody>
      </table>
    </div>
  <?php endif; ?>
</div>

<?php require_once BASE_PATH . '/includes/footer.php'; ?>
