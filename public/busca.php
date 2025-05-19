<?php
include_once '../includes/config.php';
include_once '../includes/header.php';

// Captura filtros
$busca = trim($_GET['q'] ?? '');
$status = $_GET['status'] ?? '';
$categoria = $_GET['categoria'] ?? '';

// Buscar categorias salvas do tipo 'categoria'
$cat_stmt = $conn->prepare("SELECT nome FROM tags WHERE tipo = 'categoria' ORDER BY nome ASC");
$cat_stmt->execute();
$categorias_disponiveis = $cat_stmt->get_result()->fetch_all(MYSQLI_ASSOC);

$where = [];
$params = [];
$types = "";

// Filtro: busca por título, autor, editora ou tags
if ($busca !== "") {
  $where[] = "(titulo LIKE ? OR autor LIKE ? OR editora LIKE ? OR tags LIKE ?)";
  for ($i = 0; $i < 4; $i++) {
    $params[] = "%$busca%";
    $types .= "s";
  }
}

// Filtro: status
if ($status !== "") {
  $where[] = "status = ?";
  $params[] = $status;
  $types .= "s";
}

// Filtro: categoria
if ($categoria !== "") {
  $where[] = "categoria_padrao LIKE ?";
  $params[] = "%$categoria%";
  $types .= "s";
}

$sql = "SELECT * FROM livros";
if (!empty($where)) {
  $sql .= " WHERE " . implode(" AND ", $where);
}
$sql .= " ORDER BY criado_em DESC";

$stmt = $conn->prepare($sql);
if (!empty($params)) {
  $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$result = $stmt->get_result();
?>

<div class="container resultado-container py-4">
  <h3 class="mb-4">
    <?= $busca ? "📚 Resultados para: <em>" . htmlspecialchars($busca) . "</em>" : "📚 Todos os livros cadastrados" ?>
  </h3>

  <!-- Filtros -->
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

  <!-- Resultados -->
  <div class="row">
    <?php if ($result->num_rows > 0): ?>
      <?php while ($livro = $result->fetch_assoc()): ?>
        <div class="col-md-6 col-lg-4 mb-4">
          <div class="card h-100 shadow-sm">
            <?php if (!empty($livro['capa'])): ?>
              <img src="../uploads/capas/<?= htmlspecialchars($livro['capa']) ?>" class="card-img-top" alt="Capa do livro">
            <?php elseif (!empty($livro['capa_url'])): ?>
              <img src="<?= htmlspecialchars($livro['capa_url']) ?>" class="card-img-top" alt="Capa do livro">
            <?php endif; ?>
            <div class="card-body d-flex flex-column">
              <h5 class="card-title"><?= htmlspecialchars($livro['titulo']) ?></h5>
              <p class="card-text mb-1"><strong>Autor:</strong> <?= htmlspecialchars($livro['autor']) ?></p>
              <p class="card-text mb-1"><strong>Editora:</strong> <?= htmlspecialchars($livro['editora'] ?? '-') ?></p>
              <p class="card-text mb-1"><strong>Categoria:</strong> <?= htmlspecialchars($livro['categoria_padrao'] ?? '-') ?></p>
              <p class="card-text mb-1"><strong>Tags:</strong> <?= htmlspecialchars($livro['tags'] ?? '-') ?></p>
              <p class="card-text mb-2"><strong>Status:</strong>
                <span class="badge bg-<?= $livro['status'] === 'disponivel' ? 'success' : ($livro['status'] === 'reservado' ? 'warning' : 'danger') ?>">
                  <?= ucfirst($livro['status']) ?>
                </span>
              </p>
              <a href="detalhes.php?id=<?= $livro['id'] ?>" class="btn btn-outline-primary mt-auto">Ver detalhes</a>
            </div>
          </div>
        </div>
      <?php endwhile; ?>
    <?php else: ?>
      <div class="col-12">
        <div class="alert alert-warning">⚠️ Nenhum livro encontrado com os filtros aplicados.</div>
      </div>
    <?php endif; ?>
  </div>
</div>

<?php include_once '../includes/footer.php'; ?>

<!-- ✅ Select2 CSS & JS CDN -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
  // Aplica Select2 na categoria
  $(document).ready(function () {
    $('#categoria').select2({
      placeholder: "Todas as categorias",
      allowClear: true,
      width: '100%'
    });
  });
</script>
