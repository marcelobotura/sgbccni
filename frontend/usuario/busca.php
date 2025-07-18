<?php
// 🔧 Exibir erros para desenvolvimento
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// 🛡️ Define BASE_PATH corretamente
define('BASE_PATH', dirname(__DIR__, 2));

// 📦 Includes principais
require_once BASE_PATH . '/backend/config/config.php';
require_once BASE_PATH . '/backend/includes/session.php';
require_once BASE_PATH . '/backend/includes/header.php';

// 🔒 Protege acesso: apenas usuários
exigir_login('usuario');

// 🔍 Filtros
$busca = trim($_GET['q'] ?? '');
$status = $_GET['status'] ?? '';
$categoria = $_GET['categoria'] ?? '';

// 🔖 Carrega categorias disponíveis
$cat_stmt = $pdo->prepare("SELECT nome FROM tags WHERE tipo = 'categoria' ORDER BY nome ASC");
$cat_stmt->execute();
$categorias_disponiveis = $cat_stmt->fetchAll();

// 🔎 Monta consulta dinâmica
$where = [];
$params = [];

if ($busca !== "") {
  $where[] = "(titulo LIKE :busca OR autor LIKE :busca OR editora LIKE :busca OR tags LIKE :busca)";
  $params[':busca'] = "%$busca%";
}
if ($status !== "") {
  $where[] = "status = :status";
  $params[':status'] = $status;
}
if ($categoria !== "") {
  $where[] = "categoria_padrao LIKE :categoria";
  $params[':categoria'] = "%$categoria%";
}

$sql = "SELECT * FROM livros";
if (!empty($where)) {
  $sql .= " WHERE " . implode(" AND ", $where);
}
$sql .= " ORDER BY criado_em DESC";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$result = $stmt->fetchAll();
?>

<div class="container resultado-container py-4">
  <h3 class="mb-4">
    <?= $busca ? "📚 Resultados para: <em>" . htmlspecialchars($busca) . "</em>" : "📚 Todos os livros cadastrados" ?>
  </h3>

  <!-- 🔍 Filtro -->
  <form method="GET" class="row g-2 mb-4">
    <div class="col-md-4">
      <input type="text" name="q" value="<?= htmlspecialchars($busca) ?>" class="form-control" placeholder="Título, autor, editora ou tag">
    </div>
    <div class="col-md-3">
      <select name="status" class="form-select">
        <option value="">Todos os status</option>
        <option value="disponivel" <?= $status === 'disponivel' ? 'selected' : '' ?>>Disponível</option>
        <option value="emprestado" <?= $status === 'emprestado' ? 'selected' : '' ?>>Emprestado</option>
        <option value="reservado" <?= $status === 'reservado' ? 'selected' : '' ?>>Reservado</option>
      </select>
    </div>
    <div class="col-md-3">
      <select name="categoria" class="form-select" id="categoria">
        <option value="">Todas as categorias</option>
        <?php foreach ($categorias_disponiveis as $cat): ?>
          <option value="<?= htmlspecialchars($cat['nome']) ?>" <?= $categoria === $cat['nome'] ? 'selected' : '' ?>>
            <?= htmlspecialchars($cat['nome']) ?>
          </option>
        <?php endforeach; ?>
      </select>
    </div>
    <div class="col-md-2">
      <button type="submit" class="btn btn-primary w-100">🔍 Filtrar</button>
    </div>
  </form>

  <!-- 📚 Resultados -->
  <div class="row">
    <?php if ($result): ?>
      <?php foreach ($result as $livro): ?>
        <div class="col-md-6 col-lg-4 mb-4">
          <div class="card h-100 shadow-sm">
            <?php
              if (!empty($livro['capa'])) {
                $caminhoCapa = URL_BASE . 'uploads/capas/' . htmlspecialchars($livro['capa']);
              } elseif (!empty($livro['capa_url'])) {
                $caminhoCapa = htmlspecialchars($livro['capa_url']);
              } else {
                $caminhoCapa = URL_BASE . 'assets/img/sem_capa.png';
              }
            ?>
            <img src="<?= $caminhoCapa ?>" class="card-img-top" alt="Capa do livro">
            <div class="card-body d-flex flex-column">
              <h5 class="card-title"><?= htmlspecialchars($livro['titulo']) ?></h5>
              <p class="card-text mb-1"><strong>Autor:</strong> <?= htmlspecialchars($livro['autor']) ?></p>
              <p class="card-text mb-1"><strong>Editora:</strong> <?= htmlspecialchars($livro['editora'] ?? '-') ?></p>
              <p class="card-text mb-1"><strong>Categoria:</strong> <?= htmlspecialchars($livro['categoria_padrao'] ?? '-') ?></p>
              <p class="card-text mb-1"><strong>Tags:</strong> <?= htmlspecialchars($livro['tags'] ?? '-') ?></p>
              <p class="card-text mb-2"><strong>Status:</strong>
                <?php
                  $badge = match ($livro['status']) {
                    'disponivel' => 'success',
                    'reservado' => 'warning',
                    default => 'danger',
                  };
                ?>
                <span class="badge bg-<?= $badge ?>"><?= ucfirst($livro['status']) ?></span>
              </p>
              <a href="detalhes.php?id=<?= $livro['id'] ?>" class="btn btn-outline-primary mt-auto">Ver detalhes</a>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    <?php else: ?>
      <div class="col-12">
        <div class="alert alert-warning">⚠️ Nenhum livro encontrado com os filtros aplicados.</div>
      </div>
    <?php endif; ?>
  </div>
</div>

<?php require_once BASE_PATH . '/backend/includes/footer.php'; ?>

<!-- 📦 Select2 (autocomplete de categoria) -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
  document.addEventListener('DOMContentLoaded', () => {
    const categoria = document.getElementById('categoria');
    if (categoria) {
      $(categoria).select2({
        placeholder: "Todas as categorias",
        allowClear: true,
        width: '100%'
      });
    }
  });
</script>
