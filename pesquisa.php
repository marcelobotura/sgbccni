<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/backend/config/config.php';
require_once __DIR__ . '/backend/includes/db.php';
require_once __DIR__ . '/backend/includes/session.php';

// 🔍 Filtro simples
$busca = trim($_GET['q'] ?? '');
$params = [];
$where = [];

if ($busca !== '') {
  $where[] = "(l.titulo LIKE :busca OR l.descricao LIKE :busca OR taut.nome LIKE :busca OR tedit.nome LIKE :busca)";
  $params[':busca'] = "%$busca%";
}

$sql = "SELECT l.*, 
        taut.nome AS autor_nome,
        tedit.nome AS editora_nome
        FROM livros l
        LEFT JOIN tags taut ON l.autor_id = taut.id
        LEFT JOIN tags tedit ON l.editora_id = tedit.id";

if (!empty($where)) {
  $sql .= " WHERE " . implode(' AND ', $where);
}
$sql .= " ORDER BY l.criado_em DESC";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$livros = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-br" data-tema="<?= htmlspecialchars($_COOKIE['modo_tema'] ?? 'claro') ?>">
<head>
  <meta charset="UTF-8">
  <title>Pesquisa de Livros - <?= NOME_SISTEMA ?></title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
  <link rel="stylesheet" href="<?= URL_BASE ?>frontend/assets/css/layout.css">
</head>
<body class="bg-light">
<div class="container py-4">
  <header class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="fw-bold text-primary">🔍 Pesquisa de Livros</h2>
    <a href="<?= URL_BASE ?>index.php" class="btn btn-secondary">← Voltar</a>
  </header>

  <!-- 🔎 Barra de busca -->
  <form method="get" class="mb-4">
    <div class="input-group input-group-lg shadow-sm">
      <input type="text" name="q" class="form-control" placeholder="Digite título, autor, editora..." value="<?= htmlspecialchars($busca) ?>">
      <button class="btn btn-primary" type="submit">
        <i class="bi bi-search"></i> Pesquisar
      </button>
    </div>
  </form>

  <!-- 📘 Resultados -->
  <?php if (count($livros) === 0): ?>
    <div class="alert alert-warning">Nenhum livro encontrado com os critérios fornecidos.</div>
  <?php else: ?>
    <div class="row row-cols-1 row-cols-md-3 g-4">
      <?php foreach ($livros as $livro): ?>
        <div class="col">
          <div class="card h-100 shadow-sm">
            <?php if (!empty($livro['capa'])): ?>
              <img src="<?= URL_BASE ?>uploads/capas/<?= htmlspecialchars($livro['capa']) ?>" class="card-img-top" style="height: 220px; object-fit: cover;">
            <?php elseif (!empty($livro['capa_url'])): ?>
              <img src="<?= htmlspecialchars($livro['capa_url']) ?>" class="card-img-top" style="height: 220px; object-fit: cover;">
            <?php else: ?>
              <div class="bg-secondary text-white text-center py-5">Sem Capa</div>
            <?php endif; ?>
            <div class="card-body text-center">
              <h6 class="card-title"><?= htmlspecialchars($livro['titulo']) ?></h6>
              <p class="small text-muted">
                <?= htmlspecialchars($livro['autor_nome'] ?? '-') ?> | <?= htmlspecialchars($livro['editora_nome'] ?? '-') ?>
              </p>
              <a href="<?= URL_BASE ?>frontend/usuario/livro.php?id=<?= $livro['id'] ?>" class="btn btn-sm btn-outline-primary" onclick="return verificarLoginOuRedirecionar();">
                <i class="bi bi-eye"></i> Ver Detalhes
              </a>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>
</div>

<!-- 🔒 Validação de login -->
<script>
function verificarLoginOuRedirecionar() {
  const usuarioLogado = <?= isset($_SESSION['usuario_id']) ? 'true' : 'false' ?>;
  if (!usuarioLogado) {
    alert("Você precisa estar logado para ver os detalhes do livro.");
    window.location.href = "<?= URL_BASE ?>frontend/login/login_user.php";
    return false;
  }
  return true;
}
</script>
</body>
</html>
