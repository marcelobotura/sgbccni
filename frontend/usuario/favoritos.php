<?php
define('BASE_PATH', dirname(__DIR__, 2)); // Vai até /sgbccni
require_once BASE_PATH . '/backend/config/config.php';
require_once BASE_PATH . '/backend/includes/session.php';
require_once BASE_PATH . '/backend/includes/header.php';

exigir_login('usuario');

// ID do usuário
$usuario_id = $_SESSION['usuario_id'] ?? 0;

// Busca livros favoritos com JOIN para pegar título e autor
$sql = "SELECT l.id, l.titulo, 
               (SELECT nome FROM tags WHERE id = l.autor_id) AS autor,
               l.capa
        FROM livros_usuarios lu
        JOIN livros l ON lu.livro_id = l.id
        WHERE lu.usuario_id = :usuario_id AND lu.favorito = 1
        ORDER BY l.titulo ASC";

$stmt = $pdo->prepare($sql);
$stmt->execute([':usuario_id' => $usuario_id]);
$livros = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="container py-4">
  <h2 class="mb-4">⭐ Meus Favoritos</h2>

  <?php if (empty($livros)): ?>
    <div class="alert alert-secondary text-center">Você ainda não marcou nenhum livro como favorito.</div>
  <?php else: ?>
    <div class="row">
      <?php foreach ($livros as $livro): ?>
        <div class="col-md-6 col-lg-4 mb-4">
          <div class="card h-100 shadow-sm">
            <?php
              $capa = !empty($livro['capa']) 
                      ? URL_BASE . 'uploads/capas/' . htmlspecialchars($livro['capa']) 
                      : URL_BASE . 'assets/img/sem_capa.png';
            ?>
            <img src="<?= $capa ?>" class="card-img-top" alt="Capa do livro">

            <div class="card-body d-flex flex-column">
              <h5 class="card-title"><?= htmlspecialchars($livro['titulo']) ?></h5>
              <p class="card-text mb-3"><strong>Autor:</strong> <?= htmlspecialchars($livro['autor'] ?? 'Autor desconhecido') ?></p>
              <a href="detalhes.php?id=<?= $livro['id'] ?>" class="btn btn-outline-primary mt-auto">Ver detalhes</a>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>
</div>

<?php require_once BASE_PATH . '/backend/includes/footer.php'; ?>
