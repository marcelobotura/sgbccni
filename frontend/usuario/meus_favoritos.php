<?php
require_once __DIR__ . '/../../backend/config/config.php';
require_once __DIR__ . '/../../backend/config/env.php';
require_once __DIR__ . '/../../backend/includes/session.php';
require_once __DIR__ . '/../../backend/includes/header.php';
require_once __DIR__ . '/../../backend/includes/menu.php';

exigir_login('usuario');
$usuario_id = $_SESSION['usuario_id'];

// 🔁 Remoção de favorito
if (isset($_GET['remover'])) {
  $isbn = $_GET['remover'];
  $stmt = $conn->prepare("UPDATE livros_usuarios lu 
                          JOIN livros l ON l.id = lu.livro_id 
                          SET lu.favorito = 0 
                          WHERE lu.usuario_id = :usuario_id AND l.isbn = :isbn");
  $stmt->bindParam(':usuario_id', $usuario_id, PDO::PARAM_INT);
  $stmt->bindParam(':isbn', $isbn, PDO::PARAM_STR);
  $stmt->execute();

  $_SESSION['sucesso'] = "⭐ Livro removido dos favoritos.";
  header("Location: meus_favoritos.php");
  exit;
}

// 📄 Consulta favoritos
$sql = "SELECT l.titulo, l.capa_local, l.capa_url, l.isbn
        FROM livros_usuarios lu
        JOIN livros l ON l.id = lu.livro_id
        WHERE lu.usuario_id = :usuario_id AND lu.favorito = 1";

$stmt = $conn->prepare($sql);
$stmt->bindParam(':usuario_id', $usuario_id, PDO::PARAM_INT);
$stmt->execute();
$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="container py-4">
  <h2 class="mb-4">⭐ Meus Favoritos</h2>

  <?php if (isset($_SESSION['sucesso'])): ?>
    <div class="alert alert-success"><?= htmlspecialchars($_SESSION['sucesso']); unset($_SESSION['sucesso']); ?></div>
  <?php endif; ?>

  <?php if (count($result)): ?>
    <div class="row row-cols-1 row-cols-md-3 g-4">
      <?php foreach($result as $livro): ?>
        <div class="col">
          <div class="card h-100 shadow-sm">
            <?php if (!empty($livro['capa_local'])): ?>
              <img src="<?= URL_BASE . htmlspecialchars($livro['capa_local']) ?>" class="card-img-top" style="height: 250px; object-fit: cover;">
            <?php elseif (!empty($livro['capa_url'])): ?>
              <img src="<?= htmlspecialchars($livro['capa_url']) ?>" class="card-img-top" style="height: 250px; object-fit: cover;">
            <?php else: ?>
              <div class="bg-light text-muted text-center p-5" style="height: 250px;">Sem capa</div>
            <?php endif; ?>

            <div class="card-body">
              <h5 class="card-title"><?= htmlspecialchars($livro['titulo']) ?></h5>
              <a href="livro.php?isbn=<?= urlencode($livro['isbn']) ?>" class="btn btn-sm btn-primary mb-1">📘 Ver Livro</a>
              <a href="?remover=<?= urlencode($livro['isbn']) ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Remover dos favoritos?')">🗑️ Remover</a>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  <?php else: ?>
    <div class="alert alert-info">Você ainda não adicionou livros aos favoritos.</div>
  <?php endif; ?>
</div>

<?php require_once __DIR__ . '/../../backend/includes/footer.php'; ?>
