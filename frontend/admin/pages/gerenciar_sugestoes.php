<?php 
define('BASE_PATH', dirname(__DIR__, 3));
require_once BASE_PATH . '/backend/config/config.php';
require_once BASE_PATH . '/backend/includes/session.php';
require_once BASE_PATH . '/backend/includes/header.php';
require_once BASE_PATH . '/backend/includes/menu.php';

exigir_login('admin');

// Filtros
$filtro_lidas = $_GET['filtro'] ?? 'todas';

$where = '';
if ($filtro_lidas === 'nao_lidas') {
    $where = 'WHERE s.visualizado = 0';
} elseif ($filtro_lidas === 'lidas') {
    $where = 'WHERE s.visualizado = 1';
}

$sql = "SELECT s.*, u.nome AS nome_usuario, u.email 
        FROM sugestoes s 
        JOIN usuarios u ON s.usuario_id = u.id 
        $where
        ORDER BY s.data_envio DESC";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$sugestoes = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="container py-4">
  <div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="bi bi-lightbulb"></i> Sugestões dos Usuários</h2>
  </div>

  <!-- Filtro -->
  <form method="get" class="mb-3">
    <div class="row g-2 align-items-center">
      <div class="col-auto">
        <label for="filtro" class="col-form-label">Filtrar por:</label>
      </div>
      <div class="col-auto">
        <select name="filtro" id="filtro" class="form-select" onchange="this.form.submit()">
          <option value="todas" <?= $filtro_lidas === 'todas' ? 'selected' : '' ?>>📋 Todas</option>
          <option value="nao_lidas" <?= $filtro_lidas === 'nao_lidas' ? 'selected' : '' ?>>📨 Não lidas</option>
          <option value="lidas" <?= $filtro_lidas === 'lidas' ? 'selected' : '' ?>>✅ Já lidas</option>
        </select>
      </div>
    </div>
  </form>

  <?php if (empty($sugestoes)): ?>
    <div class="alert alert-info">Nenhuma sugestão encontrada.</div>
  <?php else: ?>
    <div class="list-group">
      <?php foreach ($sugestoes as $s): ?>
        <div class="list-group-item mb-3 shadow-sm">
          <div class="d-flex justify-content-between flex-wrap">
            <div class="flex-grow-1 me-3">
              <h5 class="mb-1">
                <i class="bi bi-person-circle"></i>
                <?= htmlspecialchars($s['nome_usuario']) ?>
                <small class="text-muted">(<?= htmlspecialchars($s['email']) ?>)</small>
              </h5>
              <p class="mb-1"><?= nl2br(htmlspecialchars($s['mensagem'])) ?></p>
              <small class="text-muted">Enviada em <?= date('d/m/Y H:i', strtotime($s['data_envio'])) ?></small>

              <?php if (!empty($s['resposta'])): ?>
                <hr>
                <p class="mb-1"><strong>Resposta enviada:</strong><br><?= nl2br(htmlspecialchars($s['resposta'])) ?></p>
                <small class="text-muted">Respondida em <?= date('d/m/Y H:i', strtotime($s['respondido_em'])) ?></small>
              <?php else: ?>
                <!-- Formulário para responder -->
                <form action="<?= URL_BASE ?>backend/controllers/sugestoes/salvar_resposta.php" method="POST" class="mt-2">
                  <input type="hidden" name="id" value="<?= $s['id'] ?>">
                  <textarea name="resposta" class="form-control mb-2" rows="3" placeholder="Escreva sua resposta aqui..." required></textarea>
                  <button type="submit" class="btn btn-sm btn-primary"><i class="bi bi-reply-fill"></i> Enviar Resposta</button>
                </form>
              <?php endif; ?>
            </div>
            <div class="text-end mt-2">
              <?php if (!$s['visualizado']): ?>
                <a href="<?= URL_BASE ?>backend/controllers/sugestoes/marcar_lida.php?id=<?= $s['id'] ?>"
                   class="btn btn-sm btn-outline-success">
                  <i class="bi bi-check2-square"></i> Marcar como lida
                </a>
              <?php else: ?>
                <span class="badge bg-success"><i class="bi bi-check-circle"></i> Lida</span>
              <?php endif; ?>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>
</div>

<?php require_once BASE_PATH . '/backend/includes/footer.php'; ?>
