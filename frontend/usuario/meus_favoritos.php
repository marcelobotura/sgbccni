<?php
define('BASE_PATH', dirname(__DIR__) . '/../backend');
require_once BASE_PATH . '/config/config.php';
require_once BASE_PATH . '/includes/session.php';
include_once BASE_PATH . '/includes/header.php';

exigir_login('usuario');
$usuario_id = $_SESSION['usuario_id'];

// 🔁 Se houve pedido para remover
if (isset($_GET['remover'])) {
  $isbn = $_GET['remover'];
  $stmt = $conn->prepare("UPDATE livros_usuarios lu 
                          JOIN livros l ON l.id = lu.livro_id 
                          SET lu.favorito = 0 
                          WHERE lu.usuario_id = ? AND l.isbn = ?");
  $stmt->bind_param("is", $usuario_id, $isbn);
  $stmt->execute();
  $_SESSION['sucesso'] = "⭐ Livro removido dos favoritos.";
  header("Location: meus_favoritos.php");
  exit;
}

// 📄 Carrega favoritos
$sql = "SELECT l.titulo, l.capa_url, l.isbn
        FROM livros_usuarios lu
        JOIN livros l ON l.id = lu.livro_id
        WHERE lu.usuario_id = ? AND lu.favorito = 1";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $usuario_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<div class="container py-4">
  <h2>⭐ Meus Favoritos</h2>
  <?php if (isset($_SESSION['sucesso'])): ?>
    <div class="alert alert-success"><?= $_SESSION['sucesso']; unset($_SESSION['sucesso']); ?></div>
  <?php endif; ?>

  <?php if ($result->num_rows): ?>
    <div class="row row-cols-1 row-cols-md-3 g-4">
      <?php while($livro = $result->fetch_assoc()): ?>
        <div class="col">
          <div class="card h-100 shadow-sm">
            <?php if ($livro['capa_url']): ?>
              <img src="<?= $livro['capa_url'] ?>" class="card-img-top" style="height: 250px; object-fit: cover;">
            <?php endif; ?>
            <div class="card-body">
              <h5 class="card-title"><?= htmlspecialchars($livro['titulo']) ?></h5>
              <a href="livro.php?isbn=<?= urlencode($livro['isbn']) ?>" class="btn btn-sm btn-primary mb-1">📘 Ver Livro</a>
              <a href="?remover=<?= urlencode($livro['isbn']) ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Remover dos favoritos?')">🗑️ Remover</a>
            </div>
          </div>
        </div>
      <?php endwhile; ?>
    </div>
  <?php else: ?>
    <div class="alert alert-info">Você ainda não adicionou livros aos favoritos.</div>
  <?php endif; ?>
</div>

<?php include_once BASE_PATH . '/includes/footer.php'; ?>
