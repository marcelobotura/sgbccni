<?php
// ✅ Página: livro.php
define('BASE_PATH', dirname(__DIR__) . '/backend');
require_once BASE_PATH . '/config/config.php';
require_once BASE_PATH . '/includes/session.php';
include_once BASE_PATH . '/includes/header.php';

$isbn = $_GET['isbn'] ?? '';
if (!$isbn) {
  echo "<div class='container py-5'><div class='alert alert-danger'>ISBN não informado.</div></div>";
  include_once BASE_PATH . '/includes/footer.php';
  exit;
}

// 🔍 Busca livro pelo ISBN
$stmt = $conn->prepare("SELECT * FROM livros WHERE isbn = ?");
$stmt->bind_param("s", $isbn);
$stmt->execute();
$result = $stmt->get_result();
$livro = $result->fetch_assoc();

if (!$livro) {
  echo "<div class='container py-5'><div class='alert alert-warning'>Livro não encontrado.</div></div>";
  include_once BASE_PATH . '/includes/footer.php';
  exit;
}

$livro_id = $livro['id'];
$usuario_id = $_SESSION['usuario_id'] ?? null;

// 📌 Ações: Marcar como Lido ou Favorito
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $usuario_id) {
  if ($_POST['acao'] === 'lido') {
    $stmt = $conn->prepare("INSERT INTO livros_usuarios (usuario_id, livro_id, lido) 
                            VALUES (?, ?, 1) 
                            ON DUPLICATE KEY UPDATE lido = 1");
  } elseif ($_POST['acao'] === 'favorito') {
    $stmt = $conn->prepare("INSERT INTO livros_usuarios (usuario_id, livro_id, favorito) 
                            VALUES (?, ?, 1) 
                            ON DUPLICATE KEY UPDATE favorito = 1");
  }
  if (isset($stmt)) {
    $stmt->bind_param("ii", $usuario_id, $livro_id);
    $stmt->execute();
    $_SESSION['sucesso'] = "✅ Ação registrada com sucesso!";
    header("Location: livro.php?isbn=" . urlencode($isbn));
    exit;
  }
}

// 📚 Livros relacionados (mesmo autor ou editora)
$relacionados = $conn->prepare("SELECT id, isbn, titulo, capa_url 
                                FROM livros 
                                WHERE id != ? AND (autor = ? OR editora = ?) 
                                LIMIT 4");
$relacionados->bind_param("iss", $livro_id, $livro['autor'], $livro['editora']);
$relacionados->execute();
$rel_res = $relacionados->get_result();
?>

<div class="container py-5">
  <?php if (isset($_SESSION['sucesso'])): ?>
    <div class="alert alert-success"><?= htmlspecialchars($_SESSION['sucesso']); unset($_SESSION['sucesso']); ?></div>
  <?php endif; ?>

  <!-- 📘 Detalhes do Livro -->
  <div class="row">
    <div class="col-md-4">
      <?php if (!empty($livro['capa_url'])): ?>
        <img src="<?= htmlspecialchars($livro['capa_url']) ?>" alt="Capa" class="img-fluid rounded shadow">
      <?php else: ?>
        <div class="bg-light text-muted text-center p-5 rounded">Sem capa disponível</div>
      <?php endif; ?>
    </div>
    <div class="col-md-8">
      <h2><?= htmlspecialchars($livro['titulo']) ?></h2>
      <p><strong>Autor(es):</strong> <?= htmlspecialchars($livro['autor']) ?></p>
      <p><strong>Ano:</strong> <?= htmlspecialchars($livro['ano']) ?></p>
      <p><strong>Editora:</strong> <?= htmlspecialchars($livro['editora']) ?></p>
      <p><strong>Páginas:</strong> <?= htmlspecialchars($livro['paginas']) ?></p>
      <p><strong>Idioma:</strong> <?= htmlspecialchars($livro['idioma']) ?></p>
      <p><strong>ISBN:</strong> <?= htmlspecialchars($livro['isbn']) ?></p>
      <p><strong>Descrição:</strong><br><?= nl2br(htmlspecialchars($livro['descricao'])) ?></p>

      <?php if ($usuario_id): ?>
        <form method="POST" class="mt-3 d-flex gap-2 flex-wrap">
          <button name="acao" value="lido" class="btn btn-outline-success">📖 Marcar como Lido</button>
          <button name="acao" value="favorito" class="btn btn-outline-warning">⭐ Adicionar aos Favoritos</button>
          <a href="exportar_pdf_livro.php?isbn=<?= urlencode($livro['isbn']) ?>" class="btn btn-outline-danger">📄 Exportar PDF</a>
        </form>
      <?php else: ?>
        <div class="alert alert-info mt-3">Faça login para marcar como lido ou favorito.</div>
      <?php endif; ?>
    </div>
  </div>

  <!-- 📚 Livros Relacionados -->
  <?php if ($rel_res->num_rows > 0): ?>
    <hr>
    <h4>📚 Livros Relacionados</h4>
    <div class="row row-cols-1 row-cols-md-4 g-4 mt-2">
      <?php while($r = $rel_res->fetch_assoc()): ?>
        <div class="col">
          <div class="card h-100 shadow-sm">
            <?php if (!empty($r['capa_url'])): ?>
              <img src="<?= htmlspecialchars($r['capa_url']) ?>" class="card-img-top" style="height: 200px; object-fit: cover;" alt="Capa">
            <?php endif; ?>
            <div class="card-body">
              <h6 class="card-title text-truncate"><?= htmlspecialchars($r['titulo']) ?></h6>
              <a href="livro.php?isbn=<?= urlencode($r['isbn']) ?>" class="btn btn-sm btn-primary">Ver Detalhes</a>
            </div>
          </div>
        </div>
      <?php endwhile; ?>
    </div>
  <?php endif; ?>
</div>

<?php include_once BASE_PATH . '/includes/footer.php'; ?>
