<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

define('BASE_PATH', dirname(__DIR__, 2));
require_once BASE_PATH . '/backend/config/config.php';
require_once BASE_PATH . '/backend/includes/session.php';
require_once BASE_PATH . '/backend/includes/header.php';

exigir_login('usuario');

$busca = trim($_GET['q'] ?? '');
$status = $_GET['status'] ?? '';
$categoria = $_GET['categoria'] ?? '';
$tipo = $_GET['tipo'] ?? '';
$formato = $_GET['formato'] ?? '';
$ano_inicio = $_GET['ano_inicio'] ?? '';
$ano_fim = $_GET['ano_fim'] ?? '';

// Categorias para filtro
$cat_stmt = $pdo->prepare("SELECT nome FROM tags WHERE tipo = 'categoria' ORDER BY nome ASC");
$cat_stmt->execute();
$categorias_disponiveis = $cat_stmt->fetchAll(PDO::FETCH_COLUMN);

// Montar SQL
$where = [];
$params = [];

if ($busca !== '') {
  $where[] = "(l.titulo LIKE :busca OR taut.nome LIKE :busca OR tedit.nome LIKE :busca OR l.descricao LIKE :busca)";
  $params[':busca'] = "%$busca%";
}
if ($status !== '') {
  $where[] = "l.status = :status";
  $params[':status'] = $status;
}
if ($tipo !== '') {
  $where[] = "l.tipo = :tipo";
  $params[':tipo'] = $tipo;
}
if ($formato !== '') {
  $where[] = "l.formato = :formato";
  $params[':formato'] = $formato;
}
if ($categoria !== '') {
  $where[] = "tcat.nome LIKE :categoria";
  $params[':categoria'] = "%$categoria%";
}
if ($ano_inicio !== '' && is_numeric($ano_inicio)) {
  $where[] = "l.ano >= :ano_inicio";
  $params[':ano_inicio'] = $ano_inicio;
}
if ($ano_fim !== '' && is_numeric($ano_fim)) {
  $where[] = "l.ano <= :ano_fim";
  $params[':ano_fim'] = $ano_fim;
}

$sql = "SELECT l.*, 
        taut.nome AS autor_nome,
        tedit.nome AS editora_nome,
        tcat.nome AS categoria_nome
        FROM livros l
        LEFT JOIN tags taut ON l.autor_id = taut.id
        LEFT JOIN tags tedit ON l.editora_id = tedit.id
        LEFT JOIN tags tcat ON l.categoria_id = tcat.id";

if ($where) {
  $sql .= " WHERE " . implode(" AND ", $where);
}
$sql .= " ORDER BY l.criado_em DESC";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$resultados = $stmt->fetchAll();
?>

<div class="container mt-4">
  <div class="row">
    <div class="col-md-3">
      <form method="GET" class="border p-3 rounded shadow-sm bg-light">
        <h5 class="mb-3">🔎 Filtros</h5>

        <div class="mb-2">
          <label>Palavra-chave</label>
          <input type="text" name="q" class="form-control" value="<?= htmlspecialchars($busca) ?>">
        </div>

        <div class="mb-2">
          <label>Tipo</label>
          <select name="tipo" class="form-select">
            <option value="">Todos</option>
            <option value="digital" <?= $tipo === 'digital' ? 'selected' : '' ?>>Digital</option>
            <option value="físico" <?= $tipo === 'físico' ? 'selected' : '' ?>>Físico</option>
          </select>
        </div>

        <div class="mb-2">
          <label>Formato</label>
          <select name="formato" class="form-select">
            <option value="">Todos</option>
            <option value="PDF" <?= $formato === 'PDF' ? 'selected' : '' ?>>PDF</option>
            <option value="EPUB" <?= $formato === 'EPUB' ? 'selected' : '' ?>>EPUB</option>
            <option value="Online" <?= $formato === 'Online' ? 'selected' : '' ?>>Online</option>
          </select>
        </div>

        <div class="mb-2">
          <label>Categoria</label>
          <select name="categoria" class="form-select">
            <option value="">Todas</option>
            <?php foreach ($categorias_disponiveis as $cat): ?>
              <option value="<?= htmlspecialchars($cat) ?>" <?= $categoria === $cat ? 'selected' : '' ?>>
                <?= htmlspecialchars($cat) ?>
              </option>
            <?php endforeach; ?>
          </select>
        </div>

        <div class="mb-2">
          <label>Ano</label>
          <div class="d-flex gap-2">
            <input type="number" name="ano_inicio" class="form-control" placeholder="De" value="<?= htmlspecialchars($ano_inicio) ?>">
            <input type="number" name="ano_fim" class="form-control" placeholder="Até" value="<?= htmlspecialchars($ano_fim) ?>">
          </div>
        </div>

        <div class="d-grid gap-2 mt-3">
          <button type="submit" class="btn btn-primary">🔍 Buscar</button>
          <a href="busca.php" class="btn btn-outline-secondary">🔄 Limpar</a>
        </div>
      </form>
    </div>

    <div class="col-md-9">
      <h4 class="mb-3">📘 Resultados</h4>

      <?php if ($resultados): ?>
        <?php foreach ($resultados as $livro): ?>
          <div class="card mb-3 shadow-sm">
            <div class="card-body">
              <h5 class="card-title">
                <?= htmlspecialchars($livro['titulo']) ?> <small class="text-muted">(<?= $livro['ano'] ?? '-' ?>)</small>
              </h5>
              <p class="card-text mb-1">
                <strong>Autor:</strong> <?= htmlspecialchars($livro['autor_nome'] ?? '-') ?> |
                <strong>Editora:</strong> <?= htmlspecialchars($livro['editora_nome'] ?? '-') ?> |
                <strong>Categoria:</strong> <?= htmlspecialchars($livro['categoria_nome'] ?? '-') ?>
              </p>
              <p class="card-text small text-muted"><?= mb_strimwidth(strip_tags($livro['descricao']), 0, 200, '...') ?></p>
              <div class="d-flex justify-content-between">
                <span><strong>Status:</strong>
                  <span class="badge bg-<?= $livro['status'] === 'disponivel' ? 'success' : 'danger' ?>">
                    <?= ucfirst($livro['status']) ?>
                  </span>
                </span>
                <a href="livro.php?id=<?= $livro['id'] ?>" class="btn btn-sm btn-outline-primary">🔎 Ver Detalhes</a>
              </div>
            </div>
          </div>
        <?php endforeach; ?>
      <?php else: ?>
        <div class="alert alert-warning">⚠️ Nenhum resultado encontrado.</div>
      <?php endif; ?>
    </div>
  </div>
</div>

<?php require_once BASE_PATH . '/backend/includes/footer.php'; ?>
