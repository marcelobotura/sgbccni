<?php
define('BASE_PATH', dirname(__DIR__, 2) . '/backend');
require_once BASE_PATH . '/config/config.php';
require_once BASE_PATH . '/includes/session.php';
require_once BASE_PATH . '/includes/header.php';
require_once __DIR__ . '/protect_usuario.php';

exigir_login('usuario');

$usuario_id = $_SESSION['usuario_id'];
$nome_usuario = $_SESSION['usuario_nome'] ?? 'Anônimo';

// Feedback
$mensagem_sucesso = $_SESSION['sucesso'] ?? null;
$mensagem_erro = $_SESSION['erro'] ?? null;
unset($_SESSION['sucesso'], $_SESSION['erro']);

// 🔍 Buscar sugestões anteriores
$stmt = $pdo->prepare("SELECT mensagem, tipo, resposta, data_envio, respondido_em FROM sugestoes WHERE usuario_id = ? ORDER BY data_envio DESC");
$stmt->execute([$usuario_id]);
$minhas_sugestoes = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="container py-4">
  <div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="bi bi-lightbulb"></i> Enviar Sugestão ou Correção</h2>
    <a href="index.php" class="btn btn-outline-secondary"><i class="bi bi-arrow-left"></i> Voltar</a>
  </div>

  <?php if ($mensagem_sucesso): ?>
    <div class="alert alert-success"><?= htmlspecialchars($mensagem_sucesso) ?></div>
  <?php elseif ($mensagem_erro): ?>
    <div class="alert alert-danger"><?= htmlspecialchars($mensagem_erro) ?></div>
  <?php endif; ?>

  <form action="../controllers/sugestoes/enviar_sugestao.php" method="POST">
    <div class="mb-3">
      <label class="form-label">Tipo de sugestão ou observação:</label>
      <select name="tipo" class="form-select" required>
        <option value="">Selecione uma opção...</option>
        <option value="erro_livro">📕 Correção em livro</option>
        <option value="comentario_inadequado">💬 Comentário ofensivo</option>
        <option value="erro_tecnico">🛠 Erro técnico</option>
        <option value="sugestao_funcionalidade">✨ Nova funcionalidade</option>
        <option value="pedido_livro">📚 Sugerir novo livro</option>
        <option value="outro">📝 Outro</option>
      </select>
    </div>

    <div class="mb-3">
      <label class="form-label">Descreva sua sugestão:</label>
      <textarea name="mensagem" class="form-control" rows="5" required placeholder="Detalhe sua sugestão ou correção..."></textarea>
    </div>

    <div class="text-end">
      <button type="submit" class="btn btn-purple"><i class="bi bi-send"></i> Enviar Sugestão</button>
    </div>
  </form>

  <div class="mt-5">
    <h5><i class="bi bi-info-circle"></i> Exemplos:</h5>
    <ul class="text-muted">
      <li>Livro com título ou capa errada</li>
      <li>Comentário ofensivo</li>
      <li>Erro técnico em alguma página</li>
      <li>Sugestão de melhoria</li>
      <li>Indicar livros para incluir no sistema</li>
    </ul>
  </div>

  <?php if (!empty($minhas_sugestoes)): ?>
    <hr>
    <h5 class="mt-4"><i class="bi bi-list-check"></i> Minhas Sugestões Enviadas</h5>
    <div class="list-group mt-3">
      <?php foreach ($minhas_sugestoes as $s): ?>
        <div class="list-group-item mb-2">
          <div><strong>Tipo:</strong> <?= htmlspecialchars($s['tipo']) ?></div>
          <div><strong>Mensagem:</strong> <?= nl2br(htmlspecialchars($s['mensagem'])) ?></div>
          <div class="text-muted small">Enviada em: <?= date('d/m/Y H:i', strtotime($s['data_envio'])) ?></div>
          <?php if (!empty($s['resposta'])): ?>
            <hr>
            <div class="text-success"><strong>Resposta do Admin:</strong> <?= nl2br(htmlspecialchars($s['resposta'])) ?></div>
            <div class="text-muted small">Respondida em: <?= date('d/m/Y H:i', strtotime($s['respondido_em'])) ?></div>
          <?php endif; ?>
        </div>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>
</div>

<?php require_once BASE_PATH . '/includes/footer.php'; ?>

<style>
  .btn-purple {
    background-color: #6f42c1;
    border-color: #6f42c1;
    color: #fff;
  }
  .btn-purple:hover {
    background-color: #5a32a3;
    border-color: #5a32a3;
  }
</style>
