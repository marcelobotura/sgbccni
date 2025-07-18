<?php
require_once __DIR__ . '/../../backend/config/config.php';
require_once __DIR__ . '/../../backend/includes/session.php';
require_once __DIR__ . '/../../backend/includes/header.php';
require_once __DIR__ . '/../../backend/includes/menu.php';
require_once __DIR__ . '/protect_usuario.php';

exigir_login('usuario');
$usuario_id = $_SESSION['usuario_id'] ?? 0;

// 📝 Salvar observação
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['livro_id'])) {
  $observacao = trim($_POST['observacao'] ?? '');
  $livro_id = (int)$_POST['livro_id'];

  $stmt = $pdo->prepare("UPDATE livros_usuarios SET observacao = ? WHERE usuario_id = ? AND livro_id = ?");
  $stmt->execute([$observacao, $usuario_id, $livro_id]);

  $_SESSION['sucesso'] = "✅ Observação salva com sucesso!";
  header("Location: meus_livros.php");
  exit;
}

// 📚 Consulta livros lidos
$sql = "SELECT l.id, l.titulo, l.capa_local, l.capa_url, lu.data_leitura, lu.observacao
        FROM livros_usuarios lu
        JOIN livros l ON l.id = lu.livro_id
        WHERE lu.usuario_id = ? AND lu.lido = 1";

$stmt = $pdo->prepare($sql);
$stmt->execute([$usuario_id]);
$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="container py-4">
  <h2>📖 Meus Livros Lidos</h2>

  <?php if (isset($_SESSION['sucesso'])): ?>
    <div class="alert alert-success"><?= htmlspecialchars($_SESSION['sucesso']); unset($_SESSION['sucesso']); ?></div>
  <?php endif; ?>

  <?php if (count($result)): ?>
    <div class="row row-cols-1 row-cols-md-3 g-4">
      <?php foreach($result as $livro): ?>
        <div class="col">
          <div class="card h-100 shadow-sm">
            <?php if (!empty($livro['capa_local'])): ?>
              <img src="<?= URL_BASE . htmlspecialchars($livro['capa_local']) ?>" class="card-img-top" style="height: 250px; object-fit: cover;" alt="Capa do livro">
            <?php elseif (!empty($livro['capa_url'])): ?>
              <img src="<?= htmlspecialchars($livro['capa_url']) ?>" class="card-img-top" style="height: 250px; object-fit: cover;" alt="Capa do livro">
            <?php else: ?>
              <div class="bg-light text-muted text-center p-5" style="height: 250px;">Sem capa</div>
            <?php endif; ?>

            <div class="card-body">
              <h5 class="card-title"><?= htmlspecialchars($livro['titulo']) ?></h5>
              <p class="card-text"><strong>Lido em:</strong>
                <?= $livro['data_leitura'] ? date('d/m/Y', strtotime($livro['data_leitura'])) : '—' ?>
              </p>

              <form method="POST" class="mt-2">
                <input type="hidden" name="livro_id" value="<?= $livro['id'] ?>">
                <label for="obs_<?= $livro['id'] ?>"><strong>Observação:</strong></label>
                <textarea name="observacao" id="obs_<?= $livro['id'] ?>" class="form-control mb-2" rows="3"><?= htmlspecialchars($livro['observacao'] ?? '') ?></textarea>
                <button type="submit" class="btn btn-sm btn-outline-primary">💾 Salvar</button>
              </form>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  <?php else: ?>
    <div class="alert alert-info">Você ainda não marcou livros como lidos.</div>
  <?php endif; ?>
</div>

<?php require_once __DIR__ . '/../../backend/includes/footer.php'; ?>
