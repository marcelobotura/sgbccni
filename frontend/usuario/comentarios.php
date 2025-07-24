<?php
define('BASE_PATH', dirname(__DIR__, 2) . '/backend');
require_once BASE_PATH . '/config/config.php';
require_once BASE_PATH . '/includes/session.php';
require_once BASE_PATH . '/includes/header.php';
require_once __DIR__ . '/protect_usuario.php';

exigir_login('usuario');
$usuario_id = $_SESSION['usuario_id'] ?? null;

// 📌 Filtros
$filtro_titulo = trim($_GET['titulo'] ?? '');
$filtro_data = trim($_GET['data'] ?? '');

// 🔍 WHERE dinâmico
$where = "c.usuario_id = :usuario_id";
$params = [':usuario_id' => $usuario_id];

if (!empty($filtro_titulo)) {
    $where .= " AND l.titulo LIKE :titulo";
    $params[':titulo'] = '%' . $filtro_titulo . '%';
}
if (!empty($filtro_data)) {
    $where .= " AND DATE(c.criado_em) = :data";
    $params[':data'] = $filtro_data;
}

// 🔢 Paginação
$pagina = max(1, intval($_GET['pagina'] ?? 1));
$limite = 5;
$offset = ($pagina - 1) * $limite;

// 📊 Contagem total para paginação
$countSql = "SELECT COUNT(*) FROM comentarios c JOIN livros l ON c.livro_id = l.id WHERE $where";
$stmt = $pdo->prepare($countSql);
$stmt->execute($params);
$total = $stmt->fetchColumn();
$totalPaginas = ceil($total / $limite);

// 🔍 Consulta com paginação (sem placeholder em LIMIT/OFFSET)
$sql = "
SELECT c.id, c.texto AS comentario, c.criado_em, l.id AS livro_id, l.titulo
FROM comentarios c
JOIN livros l ON c.livro_id = l.id
WHERE $where
ORDER BY c.criado_em DESC
LIMIT $limite OFFSET $offset
";
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$comentarios = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="container py-4">
  <div class="d-flex justify-content-between align-items-center mb-4">
  <h2 class="mb-0"><i class="bi bi-chat-dots"></i> Meus Comentários</h2>
  <a href="index.php" class="btn btn-outline-secondary">
    ← Voltar
  </a>
</div>


  <!-- 🔎 Filtro -->
  <form class="row row-cols-md-auto g-2 align-items-end mb-4" method="get">
    <div class="col">
      <label class="form-label">Título do livro</label>
      <input type="text" name="titulo" value="<?= htmlspecialchars($filtro_titulo) ?>" class="form-control">
    </div>
    <div class="col">
      <label class="form-label">Data do comentário</label>
      <input type="date" name="data" value="<?= htmlspecialchars($filtro_data) ?>" class="form-control">
    </div>
    <div class="col">
      <button class="btn btn-outline-primary" type="submit"><i class="bi bi-filter"></i> Filtrar</button>
    </div>
  </form>

  <?php if (!empty($_SESSION['erro'])): ?>
    <div class="alert alert-danger"><?= $_SESSION['erro']; unset($_SESSION['erro']); ?></div>
  <?php elseif (!empty($_SESSION['sucesso'])): ?>
    <div class="alert alert-success"><?= $_SESSION['sucesso']; unset($_SESSION['sucesso']); ?></div>
  <?php endif; ?>

  <?php if (empty($comentarios)): ?>
    <div class="alert alert-info text-center">Nenhum comentário encontrado.</div>
  <?php else: ?>
    <div class="list-group">
      <?php foreach ($comentarios as $comentario): ?>
        <div class="list-group-item shadow-sm mb-3">
          <div class="d-flex justify-content-between align-items-center mb-2">
            <div>
              <strong>Livro:</strong>
              <a href="livro.php?id=<?= $comentario['livro_id'] ?>" class="text-decoration-none">
                <?= htmlspecialchars($comentario['titulo']) ?>
              </a>
            </div>
            <span class="text-muted small">Comentado em: <?= date('d/m/Y H:i', strtotime($comentario['criado_em'])) ?></span>
          </div>

          <div id="comentario-texto-<?= $comentario['id'] ?>" class="text-secondary">
            <?= nl2br(htmlspecialchars($comentario['comentario'])) ?>
          </div>

          <form method="POST" action="salvar_comentario.php" class="mt-3 d-none" id="form-editar-<?= $comentario['id'] ?>">
            <input type="hidden" name="comentario_id" value="<?= $comentario['id'] ?>">
            <textarea name="comentario" rows="3" class="form-control mb-2"><?= htmlspecialchars($comentario['comentario']) ?></textarea>
            <div class="d-flex justify-content-end gap-2">
              <button type="submit" class="btn btn-sm btn-success">
                <i class="bi bi-save"></i> Salvar
              </button>
              <button type="button" class="btn btn-sm btn-secondary" onclick="cancelarEdicao(<?= $comentario['id'] ?>)">
                Cancelar
              </button>
            </div>
          </form>

          <div class="d-flex justify-content-end gap-2 mt-2">
            <button class="btn btn-sm btn-outline-primary" onclick="editarComentario(<?= $comentario['id'] ?>)">
              <i class="bi bi-pencil"></i> Editar
            </button>
            <a href="excluir_comentario.php?id=<?= $comentario['id'] ?>" class="btn btn-sm btn-outline-danger"
              onclick="return confirm('Tem certeza que deseja excluir este comentário?')">
              <i class="bi bi-trash"></i> Excluir
            </a>
          </div>
        </div>
      <?php endforeach; ?>
    </div>

    <!-- 📄 Paginação -->
    <?php if ($totalPaginas > 1): ?>
      <nav class="mt-4">
        <ul class="pagination justify-content-center">
          <?php for ($i = 1; $i <= $totalPaginas; $i++): ?>
            <li class="page-item <?= ($i === $pagina) ? 'active' : '' ?>">
              <a class="page-link" href="?pagina=<?= $i ?>&titulo=<?= urlencode($filtro_titulo) ?>&data=<?= $filtro_data ?>"><?= $i ?></a>
            </li>
          <?php endfor; ?>
        </ul>
      </nav>
    <?php endif; ?>
  <?php endif; ?>
</div>

<script>
function editarComentario(id) {
  document.getElementById('comentario-texto-' + id).style.display = 'none';
  document.getElementById('form-editar-' + id).classList.remove('d-none');
}
function cancelarEdicao(id) {
  document.getElementById('comentario-texto-' + id).style.display = 'block';
  document.getElementById('form-editar-' + id).classList.add('d-none');
}
</script>

<?php require_once BASE_PATH . '/includes/footer.php'; ?>
