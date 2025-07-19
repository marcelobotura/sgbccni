<?php
// Caminho: frontend/admin/pages/ver_livro.php

define('BASE_PATH', dirname(__DIR__, 3));
require_once BASE_PATH . '/backend/config/config.php';
require_once BASE_PATH . '/backend/includes/session.php';
require_once BASE_PATH . '/backend/includes/protect_admin.php';
require_once BASE_PATH . '/backend/includes/header.php';
require_once BASE_PATH . '/backend/includes/menu.php';

exigir_login('admin');

// 🔍 ID do livro
$livro_id = intval($_GET['id'] ?? 0);

if (!$livro_id) {
    echo "<div class='container py-5'><div class='alert alert-danger'>ID do livro não informado.</div></div>";
    include BASE_PATH . '/backend/includes/footer.php';
    exit;
}

// 📚 Buscar dados do livro
$stmt = $pdo->prepare("SELECT * FROM livros WHERE id = ?");
$stmt->execute([$livro_id]);
$livro = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$livro) {
    echo "<div class='container py-5'><div class='alert alert-warning'>Livro não encontrado.</div></div>";
    include BASE_PATH . '/backend/includes/footer.php';
    exit;
}

// Buscar nomes das tags
function buscar_nome_tag($id, $pdo) {
    if (!$id) return 'N/A';
    $stmt = $pdo->prepare("SELECT nome FROM tags WHERE id = ?");
    $stmt->execute([$id]);
    $res = $stmt->fetch();
    return $res ? $res['nome'] : 'N/A';
}

$autor = buscar_nome_tag($livro['autor_id'] ?? null, $pdo);
$editora = buscar_nome_tag($livro['editora_id'] ?? null, $pdo);
$categoria = buscar_nome_tag($livro['categoria_id'] ?? null, $pdo);

// Comentários do livro
$stmt_com = $pdo->prepare("SELECT c.*, u.nome FROM comentarios c
                           JOIN usuarios u ON c.usuario_id = u.id
                           WHERE c.livro_id = ? ORDER BY c.criado_em DESC");
$stmt_com->execute([$livro_id]);
$comentarios = $stmt_com->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="container py-4">
  <h2 class="mb-4">📘 Detalhes do Livro</h2>

  <div class="row mb-4">
    <div class="col-md-3 text-center">
      <?php
        $capa = (!empty($livro['capa']) && file_exists(BASE_PATH . '/../uploads/capas/' . $livro['capa']))
          ? URL_BASE . 'uploads/capas/' . $livro['capa']
          : (!empty($livro['capa_url']) ? $livro['capa_url'] : URL_BASE . 'assets/img/sem_capa.png');
      ?>
      <img src="<?= $capa ?>" alt="Capa" class="img-fluid rounded shadow" style="max-height: 300px;">
    </div>
    <div class="col-md-9">
      <h4><?= htmlspecialchars($livro['titulo']) ?></h4>
      <p><strong>Autor:</strong> <?= $autor ?></p>
      <p><strong>Editora:</strong> <?= $editora ?></p>
      <p><strong>Categoria:</strong> <?= $categoria ?></p>
      <p><strong>ISBN:</strong> <?= htmlspecialchars($livro['isbn']) ?></p>
      <p><strong>Código Interno:</strong> <?= htmlspecialchars($livro['codigo_interno']) ?></p>
      <p><strong>Descrição:</strong><br><?= nl2br(htmlspecialchars($livro['descricao'])) ?></p>
    </div>
  </div>

  <hr>
  <h4>💬 Comentários</h4>

  <?php if ($comentarios): ?>
    <?php foreach ($comentarios as $c): ?>
      <div class="border rounded p-3 mb-3 bg-light">
        <strong><?= htmlspecialchars($c['nome']) ?></strong>
        <small class="text-muted"><?= date('d/m/Y H:i', strtotime($c['criado_em'])) ?></small>
        <p><?= nl2br(htmlspecialchars($c['texto'])) ?></p>

        <div class="d-flex gap-2">
          <?php if (!$c['aprovado']): ?>
            <a href="aprovar_comentario.php?id=<?= $c['id'] ?>" class="btn btn-sm btn-success">
              <i class="bi bi-check2-circle"></i> Aprovar
            </a>
          <?php endif; ?>
          <a href="excluir_comentario.php?id=<?= $c['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Tem certeza que deseja excluir este comentário?')">
            <i class="bi bi-trash"></i> Excluir
          </a>
        </div>
      </div>
    <?php endforeach; ?>
  <?php else: ?>
    <p class="text-muted">Nenhum comentário encontrado.</p>
  <?php endif; ?>
</div>

<?php include BASE_PATH . '/backend/includes/footer.php'; ?>
