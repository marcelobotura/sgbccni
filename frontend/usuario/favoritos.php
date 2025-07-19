<?php
require_once __DIR__ . '/../../backend/config/config.php';
require_once __DIR__ . '/../../backend/includes/session.php';
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

<?php require_once BASE_PATH . '/includes/header.php'; ?>
<div class="container py-4">
    <h2>⭐ Meus Favoritos</h2>
    <?php if (count($favoritos) === 0): ?>
        <p class="text-muted">Você ainda não adicionou livros aos favoritos.</p>
    <?php else: ?>
        <div class="row row-cols-1 row-cols-md-3 g-4">
            <?php foreach ($favoritos as $livro): ?>
                <div class="col">
                    <div class="card h-100 shadow-sm">
                        <img src="<?= !empty($livro['capa']) ? URL_BASE . 'uploads/capas/' . $livro['capa'] : ($livro['capa_url'] ?: URL_BASE . 'assets/img/sem_capa.png') ?>" class="card-img-top" alt="Capa" style="height: 220px; object-fit: cover;">
                        <div class="card-body">
                            <h5 class="card-title text-truncate"><?= htmlspecialchars($livro['titulo']) ?></h5>
                            <p class="card-text"><strong>ISBN:</strong> <?= htmlspecialchars($livro['isbn']) ?></p>
                            <a href="livro.php?id=<?= $livro['id'] ?>" class="btn btn-primary btn-sm">Ver Detalhes</a>
                            <a href="remover_favorito.php?id=<?= $livro['id'] ?>" class="btn btn-outline-danger btn-sm" onclick="return confirm('Remover dos favoritos?')">Remover</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>
<?php require_once BASE_PATH . '/includes/footer.php'; ?>
