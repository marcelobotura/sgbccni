<?php
define('BASE_PATH', dirname(__DIR__, 2) . '/backend');
require_once BASE_PATH . '/config/config.php';
require_once BASE_PATH . '/includes/session.php';
require_once BASE_PATH . '/includes/header.php';
require_once BASE_PATH . '/includes/protect_usuario.php';

exigir_login('usuario');
$usuario_id = $_SESSION['usuario_id'] ?? 0;

// 🔁 Remoção de favorito
if (isset($_GET['remover'])) {
  $isbn = $_GET['remover'];
  $stmt = $pdo->prepare("UPDATE livros_usuarios lu 
                         JOIN livros l ON l.id = lu.livro_id 
                         SET lu.favorito = 0 
                         WHERE lu.usuario_id = :usuario_id AND l.isbn = :isbn");
  $stmt->bindParam(':usuario_id', $usuario_id);
  $stmt->bindParam(':isbn', $isbn);
  $stmt->execute();

  $_SESSION['sucesso'] = "⭐ Livro removido dos favoritos.";
  header("Location: meus_favoritos.php");
  exit;
}

// 📄 Consulta favoritos
$sql = "SELECT l.titulo, l.capa AS capa_local, l.capa_url, l.isbn
        FROM livros_usuarios lu
        JOIN livros l ON l.id = lu.livro_id
        WHERE lu.usuario_id = :usuario_id AND lu.favorito = 1";

$stmt = $pdo->prepare($sql);
$stmt->bindParam(':usuario_id', $usuario_id);
$stmt->execute();
$livros = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<main class="painel-usuario container py-4">
  <header class="painel-header d-flex justify-content-between align-items-center mb-4">
    <h2><i class="bi bi-star-fill"></i> Meus Favoritos</h2>
    <a href="index.php" class="btn btn-secundario"><i class="bi bi-arrow-left"></i> Voltar</a>
  </header>

  <?php if (isset($_SESSION['sucesso'])): ?>
    <div class="alert alert-success"><?= htmlspecialchars($_SESSION['sucesso']); unset($_SESSION['sucesso']); ?></div>
  <?php endif; ?>

  <?php if ($livros): ?>
    <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-4">
      <?php foreach ($livros as $livro): ?>
        <div class="col">
          <div class="card h-100 shadow-sm">
            <?php
              $capa = !empty($livro['capa_local']) 
                ? URL_BASE . 'uploads/capas/' . htmlspecialchars($livro['capa_local']) 
                : (!empty($livro['capa_url']) ? htmlspecialchars($livro['capa_url']) : URL_BASE . 'assets/img/sem_capa.png');
            ?>
            <img src="<?= $capa ?>" class="card-img-top" style="height: 240px; object-fit: cover;" alt="Capa do livro">

            <div class="card-body d-flex flex-column text-center">
              <h5 class="card-title"><?= htmlspecialchars($livro['titulo']) ?></h5>
              <a href="livro.php?isbn=<?= urlencode($livro['isbn']) ?>" class="btn btn-sm btn-primario mt-auto">📘 Ver Livro</a>
              <a href="?remover=<?= urlencode($livro['isbn']) ?>" class="btn btn-sm btn-outline-danger mt-2" onclick="return confirm('Remover dos favoritos?')">🗑️ Remover</a>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  <?php else: ?>
    <div class="alert alert-info text-center">📚 Você ainda não adicionou livros aos favoritos.</div>
  <?php endif; ?>
</main>

<?php require_once BASE_PATH . '/includes/footer.php'; ?>
