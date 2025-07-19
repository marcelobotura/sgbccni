<?php
define('BASE_PATH', dirname(__DIR__, 2) . '/backend');
require_once BASE_PATH . '/config/config.php';
require_once BASE_PATH . '/includes/session.php';
require_once BASE_PATH . '/includes/header.php';
require_once __DIR__ . '/protect_usuario.php';

exigir_login('usuario');
$usuario_id = $_SESSION['usuario_id'] ?? 0;

// 🔁 Remoção de favorito
if (isset($_GET['remover'])) {
  $isbn = $_GET['remover'];
  $stmt = $pdo->prepare("UPDATE livros_usuarios lu 
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
$sql = "SELECT l.titulo, l.capa AS capa_local, l.capa_url, l.isbn
        FROM livros_usuarios lu
        JOIN livros l ON l.id = lu.livro_id
        WHERE lu.usuario_id = :usuario_id AND lu.favorito = 1";

$stmt = $pdo->prepare($sql);
$stmt->bindParam(':usuario_id', $usuario_id, PDO::PARAM_INT);
$stmt->execute();
$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <title>⭐ Meus Favoritos - Biblioteca CNI</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <link rel="stylesheet" href="<?= URL_BASE ?>frontend/assets/css/base.css">
  <link rel="stylesheet" href="<?= URL_BASE ?>frontend/assets/css/layout.css">
  <link rel="stylesheet" href="<?= URL_BASE ?>frontend/assets/css/components.css">
  <link rel="stylesheet" href="<?= URL_BASE ?>frontend/assets/css/pages/painel_usuario.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
</head>
<body>

<main class="painel-usuario container">
  <header class="painel-header d-flex justify-content-between align-items-center mb-4">
    <h2><i class="bi bi-star-fill"></i> Meus Favoritos</h2>
    <a href="index.php" class="btn btn-secundario"><i class="bi bi-arrow-left"></i> Voltar</a>
  </header>

  <?php if (isset($_SESSION['sucesso'])): ?>
    <div class="alert alert-success fw-bold"><?= htmlspecialchars($_SESSION['sucesso']); unset($_SESSION['sucesso']); ?></div>
  <?php endif; ?>

  <?php if (count($result)): ?>
    <div class="row row-cols-1 row-cols-md-3 g-4">
      <?php foreach ($result as $livro): ?>
        <div class="col">
          <div class="card h-100 shadow-sm border-0">
            <?php
              if (!empty($livro['capa_local'])) {
                $caminhoCapa = URL_BASE . 'uploads/capas/' . htmlspecialchars($livro['capa_local']);
              } elseif (!empty($livro['capa_url'])) {
                $caminhoCapa = htmlspecialchars($livro['capa_url']);
              } else {
                $caminhoCapa = URL_BASE . 'assets/img/sem_capa.png';
              }
            ?>
            <img src="<?= $caminhoCapa ?>" class="card-img-top" style="height: 250px; object-fit: cover;" alt="Capa do livro">

            <div class="card-body d-flex flex-column">
              <h5 class="card-title"><?= htmlspecialchars($livro['titulo']) ?></h5>
              <a href="livro.php?isbn=<?= urlencode($livro['isbn']) ?>" class="btn btn-sm btn-primario mt-auto">
                <i class="bi bi-book"></i> Ver Livro
              </a>
              <a href="?remover=<?= urlencode($livro['isbn']) ?>" class="btn btn-sm btn-outline-danger mt-2" onclick="return confirm('Remover dos favoritos?')">
                <i class="bi bi-trash3"></i> Remover
              </a>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  <?php else: ?>
    <div class="alert alert-info text-center shadow-sm">Você ainda não adicionou livros aos favoritos.</div>
  <?php endif; ?>
</main>

<?php require_once BASE_PATH . '/includes/footer.php'; ?>
</body>
</html>
