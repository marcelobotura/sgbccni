<?php
// Caminho: frontend/usuario/meus_favoritos.php
define('BASE_PATH', dirname(__DIR__, 2));
require_once BASE_PATH . '/backend/config/config.php';
require_once BASE_PATH . '/backend/includes/session.php';
require_once __DIR__ . '/protect_usuario.php';

exigir_login('usuario');
$usuario_id = $_SESSION['usuario_id'];

// 🔍 Buscar livros favoritos
$sql = "SELECT l.* FROM livros l
        JOIN livros_usuarios lu ON l.id = lu.livro_id
        WHERE lu.usuario_id = :usuario_id AND lu.favorito = 1
        ORDER BY l.titulo";
$stmt = $pdo->prepare($sql);
$stmt->execute(['usuario_id' => $usuario_id]);
$favoritos = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<?php require_once BASE_PATH . '/backend/includes/header.php'; ?>

<div class="container py-4">
    <h2>⭐ Meus Favoritos</h2>

    <?php if (count($favoritos) === 0): ?>
        <p class="text-muted">Você ainda não adicionou livros aos favoritos.</p>
    <?php else: ?>
        <div class="row row-cols-1 row-cols-md-3 g-4">
            <?php foreach ($favoritos as $livro): ?>
                <div class="col">
                    <div class="card h-100 shadow-sm border-0">
                        <?php
                            if (!empty($livro['capa_local']) && file_exists(BASE_PATH . '/storage/uploads/capas/' . $livro['capa_local'])) {
                                $caminhoCapa = URL_BASE . 'storage/uploads/capas/' . $livro['capa_local'];
                            } elseif (!empty($livro['capa_url'])) {
                                $caminhoCapa = $livro['capa_url'];
                            } else {
                                $caminhoCapa = URL_BASE . 'frontend/assets/img/livro_padrao.png';
                            }
                        ?>
                        <img src="<?= htmlspecialchars($caminhoCapa) ?>" class="card-img-top" style="height: 220px; object-fit: cover;" alt="Capa do livro">

                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title text-truncate"><?= htmlspecialchars($livro['titulo']) ?></h5>
                            <p class="card-text"><strong>ISBN:</strong> <?= htmlspecialchars($livro['isbn']) ?></p>

                            <a href="<?= URL_BASE ?>frontend/usuario/livro.php?isbn=<?= urlencode($livro['isbn']) ?>" class="btn btn-sm btn-primary mt-auto">
                                Ver Detalhes
                            </a>
                            <a href="<?= URL_BASE ?>frontend/usuario/remover_favorito.php?id=<?= urlencode($livro['id']) ?>" class="btn btn-sm btn-outline-danger mt-2" onclick="return confirm('Remover dos favoritos?')">
                                Remover
                            </a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<?php require_once BASE_PATH . '/backend/includes/footer.php'; ?>
