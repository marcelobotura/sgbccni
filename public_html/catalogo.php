<?php
// Caminho: public_html/catalogo.php
declare(strict_types=1);

ini_set('display_errors', '1');
error_reporting(E_ALL);

define('BASE_PATH', dirname(__DIR__));
require_once BASE_PATH . '/backend/config/config.php';
require_once BASE_PATH . '/backend/includes/db.php';
require_once BASE_PATH . '/backend/includes/session.php';

// Filtros
$busca = trim($_GET['q'] ?? '');
$categoria = $_GET['categoria'] ?? '';
$tipo = $_GET['tipo'] ?? '';
$formato = $_GET['formato'] ?? '';
$ano_inicio = $_GET['ano_inicio'] ?? '';
$ano_fim = $_GET['ano_fim'] ?? '';

// Carregar categorias
$cat_stmt = $pdo->prepare("SELECT nome FROM tags WHERE tipo = 'categoria' ORDER BY nome ASC");
$cat_stmt->execute();
$categorias_disponiveis = $cat_stmt->fetchAll(PDO::FETCH_COLUMN);

// Monta SQL
$where = [];
$params = [];

if ($busca !== '') {
  $where[] = "(l.titulo LIKE :busca OR taut.nome LIKE :busca OR tedit.nome LIKE :busca OR l.descricao LIKE :busca)";
  $params[':busca'] = "%$busca%";
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
$livros = $stmt->fetchAll(PDO::FETCH_ASSOC);

function capaLivro(array $livro): string {
  if (!empty($livro['capa_local']) && file_exists(BASE_PATH . '/storage/uploads/capas/' . $livro['capa_local'])) {
    return URL_BASE . 'storage/uploads/capas/' . $livro['capa_local'];
  }
  return $livro['capa_url'] ?? (URL_BASE . 'frontend/assets/img/livro_padrao.png');
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <title>Catálogo de Livros</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="<?= URL_BASE ?>frontend/assets/css/base.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
  <style>
    .livro-card:hover { cursor: pointer; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
    .detalhes-livro { border-top: 1px solid #ccc; margin-top: 30px; padding-top: 20px; display: none; }
    .estrelinhas i { color: gold; }
  </style>
</head>
<body>
<div class="container py-4">
  <h2 class="mb-4 text-center">📚 Catálogo de Livros</h2>

  <form method="GET" class="row g-3 mb-4 bg-light p-3 rounded shadow-sm">
    <div class="col-md-4">
      <input type="text" name="q" class="form-control" placeholder="🔍 Buscar por título, autor, editora..." value="<?= htmlspecialchars($busca) ?>">
    </div>
    <div class="col-md-2">
      <select name="categoria" class="form-select">
        <option value="">Todas as categorias</option>
        <?php foreach ($categorias_disponiveis as $cat): ?>
          <option value="<?= htmlspecialchars($cat) ?>" <?= $categoria === $cat ? 'selected' : '' ?>><?= htmlspecialchars($cat) ?></option>
        <?php endforeach; ?>
      </select>
    </div>
    <div class="col-md-2">
      <select name="tipo" class="form-select">
        <option value="">Tipo</option>
        <option value="digital" <?= $tipo === 'digital' ? 'selected' : '' ?>>Digital</option>
        <option value="físico" <?= $tipo === 'físico' ? 'selected' : '' ?>>Físico</option>
      </select>
    </div>
    <div class="col-md-2">
      <select name="formato" class="form-select">
        <option value="">Formato</option>
        <option value="PDF" <?= $formato === 'PDF' ? 'selected' : '' ?>>PDF</option>
        <option value="EPUB" <?= $formato === 'EPUB' ? 'selected' : '' ?>>EPUB</option>
        <option value="Online" <?= $formato === 'Online' ? 'selected' : '' ?>>Online</option>
      </select>
    </div>
    <div class="col-md-2 d-grid">
      <button type="submit" class="btn btn-primary">🔍 Buscar</button>
    </div>
  </form>

  <div class="row g-4">
    <?php foreach ($livros as $livro): ?>
      <div class="col-sm-6 col-md-4 col-lg-3 livro-card" onclick="mostrarDetalhes(<?= $livro['id'] ?>)">
        <div class="card h-100">
          <img src="<?= capaLivro($livro) ?>" class="card-img-top" alt="Capa do livro" style="height: 220px; object-fit: cover;">
          <div class="card-body">
            <h6 class="card-title"><?= htmlspecialchars($livro['titulo']) ?></h6>
            <small class="text-muted"><?= htmlspecialchars($livro['autor_nome'] ?? '-') ?></small>
          </div>
        </div>
      </div>
    <?php endforeach; ?>
  </div>

  <!-- 🔽 Detalhes do livro clicado -->
  <div id="painelDetalhes" class="detalhes-livro row mt-5"></div>
</div>

<script>
function mostrarDetalhes(id) {
  fetch('ver_livro_ajax.php?id=' + id)
    .then(resp => resp.text())
    .then(html => {
      document.getElementById('painelDetalhes').innerHTML = html;
      document.getElementById('painelDetalhes').style.display = 'flex';
      window.scrollTo({ top: document.getElementById('painelDetalhes').offsetTop - 100, behavior: 'smooth' });
    });
}
</script>
</body>
</html>
