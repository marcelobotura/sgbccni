<?php
define('BASE_PATH', dirname(__DIR__, 3));
require_once BASE_PATH . '/backend/config/config.php';
require_once BASE_PATH . '/backend/includes/session.php';
require_once BASE_PATH . '/backend/includes/protect_admin.php';
require_once BASE_PATH . '/backend/includes/header.php';

$id = $_GET['id'] ?? null;

$stmt = $pdo->prepare("SELECT s.*, u.nome FROM sugestoes s JOIN usuarios u ON s.usuario_id = u.id WHERE s.id = ?");
$stmt->execute([$id]);
$sugestao = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$sugestao) {
  echo "<div class='container py-4'><div class='alert alert-danger'>Sugestão não encontrada.</div></div>";
  exit;
}
?>

<div class="container py-4">
  <h2><i class="bi bi-reply"></i> Responder Sugestão</h2>

  <div class="card mb-3">
    <div class="card-body">
      <h5><i class="bi bi-person-circle"></i> <?= htmlspecialchars($sugestao['nome']) ?></h5>
      <p><strong>Tipo:</strong> <?= htmlspecialchars($sugestao['tipo']) ?></p>
      <p><strong>Mensagem:</strong><br><?= nl2br(htmlspecialchars($sugestao['mensagem'])) ?></p>
      <p class="text-muted small">Enviado em: <?= date('d/m/Y H:i', strtotime($sugestao['criado_em'])) ?></p>
    </div>
  </div>

  <form method="POST" action="<?= URL_BASE ?>backend/controllers/sugestoes/salvar_resposta.php">
    <input type="hidden" name="id" value="<?= $sugestao['id'] ?>">
    <div class="mb-3">
      <label class="form-label">Resposta do administrador:</label>
      <textarea name="resposta" class="form-control" rows="5" required><?= htmlspecialchars($sugestao['resposta'] ?? '') ?></textarea>
    </div>
    <button type="submit" class="btn btn-success"><i class="bi bi-send-check"></i> Salvar Resposta</button>
    <a href="gerenciar_sugestoes.php" class="btn btn-secondary">Cancelar</a>
  </form>
</div>

<?php require_once BASE_PATH . '/backend/includes/footer.php'; ?>
