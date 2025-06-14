<?php
require_once(__DIR__ . '/../config/config.php');
require_once(__DIR__ . '/../includes/session.php');
require_once(__DIR__ . '/../includes/header.php');

exigir_login('usuario');

// 📌 Verifica o ID
$id = intval($_GET['id'] ?? 0);
$usuario_id = $_SESSION['usuario_id'];

if ($id <= 0) {
    echo '<div class="container mt-5"><div class="alert alert-danger">ID inválido.</div></div>';
    require_once(__DIR__ . '/../includes/footer.php');
    exit;
}

// 🔎 Busca os dados do livro
$stmt = $conn->prepare("SELECT * FROM livros WHERE id = ?");
$stmt->execute([$id]);
$livro = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$livro) {
    echo '<div class="container mt-5"><div class="alert alert-warning">Livro não encontrado.</div></div>';
    require_once(__DIR__ . '/../includes/footer.php');
    exit;
}

// 🔎 Verifica se o livro está marcado como favorito ou lido pelo usuário
$stmtCheck = $conn->prepare("SELECT favorito, status FROM livros_usuarios WHERE usuario_id = ? AND livro_id = ?");
$stmtCheck->execute([$usuario_id, $id]);
$dadosUsuario = $stmtCheck->fetch(PDO::FETCH_ASSOC);
$favorito = $dadosUsuario['favorito'] ?? 0;
$status = $dadosUsuario['status'] ?? '';

?>
<div class="container my-5">
    <div class="card shadow-lg border-0">
        <div class="row g-0">
            <!-- 🖼️ Capa -->
            <div class="col-md-4">
                <?php if (!empty($livro['capa'])): ?>
                    <img src="<?= URL_BASE ?>uploads/capas/<?= htmlspecialchars($livro['capa']) ?>" class="img-fluid h-100 rounded-start" style="object-fit: cover;" alt="Capa do livro">
                <?php elseif (!empty($livro['capa_url'])): ?>
                    <img src="<?= htmlspecialchars($livro['capa_url']) ?>" class="img-fluid h-100 rounded-start" style="object-fit: cover;" alt="Capa do livro">
                <?php else: ?>
                    <div class="d-flex align-items-center justify-content-center h-100 bg-light text-muted p-4 rounded-start">
                        Sem imagem disponível
                    </div>
                <?php endif; ?>
            </div>

            <!-- 📖 Dados -->
            <div class="col-md-8">
                <div class="card-body">
                    <h3 class="card-title"><?= htmlspecialchars($livro['titulo']) ?></h3>
                    <?php if (!empty($livro['subtitulo'])): ?>
                        <h5 class="text-muted"><?= htmlspecialchars($livro['subtitulo']) ?></h5>
                    <?php endif; ?>

                    <ul class="list-unstyled mt-3">
                        <?php if ($livro['autor']): ?><li><strong>Autor:</strong> <?= htmlspecialchars($livro['autor']) ?></li><?php endif; ?>
                        <?php if ($livro['editora']): ?><li><strong>Editora:</strong> <?= htmlspecialchars($livro['editora']) ?></li><?php endif; ?>
                        <?php if ($livro['ano']): ?><li><strong>Ano:</strong> <?= htmlspecialchars($livro['ano']) ?></li><?php endif; ?>
                        <?php if ($livro['isbn']): ?><li><strong>ISBN:</strong> <?= htmlspecialchars($livro['isbn']) ?></li><?php endif; ?>
                        <?php if ($livro['formato']): ?><li><strong>Formato:</strong> <?= htmlspecialchars($livro['formato']) ?></li><?php endif; ?>
                        <?php if ($livro['idioma']): ?><li><strong>Idioma:</strong> <?= htmlspecialchars($livro['idioma']) ?></li><?php endif; ?>

                        <li><strong>Status:</strong>
                            <span class="badge bg-<?= 
                                $livro['status'] === 'disponivel' ? 'success' : (
                                $livro['status'] === 'reservado' ? 'warning' : 'danger'
                            ) ?>">
                                <?= ucfirst($livro['status'] ?? 'Indisponível') ?>
                            </span>
                        </li>
                    </ul>

                    <?php if ($livro['descricao']): ?>
                        <hr>
                        <p><strong>Descrição:</strong><br><?= nl2br(htmlspecialchars($livro['descricao'])) ?></p>
                    <?php endif; ?>

                    <!-- 🎯 Ações -->
                    <div class="d-flex flex-wrap gap-2 mt-4">
                        <!-- Ler Digital -->
                        <?php if (strtolower($livro['formato']) === 'digital' && !empty($livro['link_leitura'])): ?>
                            <a href="<?= htmlspecialchars($livro['link_leitura']) ?>" target="_blank" class="btn btn-success">
                                📖 Ler agora
                            </a>
                        <?php endif; ?>

                        <!-- Favorito -->
                        <?php if (!$favorito): ?>
                            <a href="acao_favorito.php?livro_id=<?= $id ?>&acao=adicionar" class="btn btn-outline-warning">
                                ⭐ Adicionar aos Favoritos
                            </a>
                        <?php else: ?>
                            <a href="acao_favorito.php?livro_id=<?= $id ?>&acao=remover" class="btn btn-warning">
                                ⭐ Remover dos Favoritos
                            </a>
                        <?php endif; ?>

                        <!-- Marcar como Lido -->
                        <?php if ($status !== 'lido'): ?>
                            <a href="marcar_lido.php" class="btn btn-outline-info"
                               onclick="event.preventDefault(); document.getElementById('form-lido').submit();">
                                ✅ Marcar como Lido
                            </a>
                            <form id="form-lido" method="POST" action="marcar_lido.php" style="display:none;">
                                <input type="hidden" name="livro_id" value="<?= $id ?>">
                            </form>
                        <?php else: ?>
                            <button class="btn btn-info" disabled>✅ Já lido</button>
                        <?php endif; ?>

                        <a href="index.php" class="btn btn-outline-primary">← Voltar</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once(__DIR__ . '/../includes/footer.php'); ?>
