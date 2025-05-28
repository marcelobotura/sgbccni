<?php
define('BASE_PATH', dirname(__DIR__) . '/../backend');
require_once BASE_PATH . '/config/config.php';
require_once BASE_PATH . '/includes/session.php';
include_once BASE_PATH . '/includes/header.php';

exigir_login('usuario');
$usuario_id = $_SESSION['usuario_id'];

// 📝 Salvar observação
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['livro_id'])) {
  $observacao = trim($_POST['observacao'] ?? '');
  $livro_id = (int)$_POST['livro_id'];

  $stmt = $conn->prepare("UPDATE livros_usuarios SET observacao = ? WHERE usuario_id = ? AND livro_id = ?");
  $stmt->bind_param("sii", $observacao, $usuario_id, $livro_id);
  $stmt->execute();

  $_SESSION['sucesso'] = "✅ Observação salva com sucesso!";
  header("Location: meus_livros.php");
  exit;
}

// 📚 Consulta livros lidos
$sql = "SELECT l.id, l.titulo, l.capa_local, l.capa_url, lu.data_leitura, lu.observacao
        FROM livros_usuarios lu
        JOIN livros l ON l.id = lu.livro_id
        WHERE lu.usuario_id = ? AND lu.lido = 1";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $usuario_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<div class="container py-4">
  <h2>📖 Meus Livros Lidos</h2>

  <?php if (isset($_SESSION['sucesso'])): ?>
    <div class="alert alert-success"><?= htmlspecialchars($_SESSION['sucesso']); unset($_SESSION['sucesso']); ?></div>
  <?php endif; ?>

  <?php if ($result->num_rows): ?>
    <div class="row row-cols-1 row-cols-md-3 g-4">
      <?php while($livro = $result->fetch_assoc()): ?>
        <div class="col">
          <div class="card h-100 shadow-sm">
            <?php if (!empty($livro['capa_local'])): ?>
              <img src="<?= URL_BASE . htmlspecialchars($livro['capa_local']) ?>" class="card-img-top" style="height: 250px; object-fit: cover;">
            <?php elseif (!empty($livro['capa_url'])): ?>
              <img src="<?= htmlspecialchars($livro['capa_url']) ?>" class="card-img-top" style="height: 250px; object-fit: cover;">
            <?php endif; ?>

            <div class="card-body">
              <h5 class="card-title"><?= htmlspecialchars($livro['titulo']) ?></h5>
              <p class="card-text"><strong>Lido em:</strong>
                <?= $livro['data_leitura'] ? date('d/m/Y', strtotime($livro['data_leitura'])) : '—' ?>
              </p>

              <form method="POST" class="mt-2">
                <input type="hidden" name="livro_id" value="<?= $livro['id'] ?>">
                <label for="obs_<?= $livro['id'] ?>"><strong>Observação:</strong></label>
                <textarea name="observacao" id="obs_<?= $livro['id'] ?>" class="form-control mb-2" rows="3"><?= htmlspecialchars($livro['observacao']) ?></textarea>
                <button type="submit" class="btn btn-sm btn-outline-primary">💾 Salvar</button>
              </form>
            </div>
          </div>
        </div>
      <?php endwhile; ?>
    </div>
  <?php else: ?>
    <div class="alert alert-info">Você ainda não marcou livros como lidos.</div>
  <?php endif; ?>
</div>

<?php include_once BASE_PATH . '/includes/footer.php'; ?>
