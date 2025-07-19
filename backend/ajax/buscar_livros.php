<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../includes/db.php';

$q = trim($_GET['q'] ?? '');

if ($q === '') {
    exit('<div class="alert alert-warning">Digite algo para pesquisar.</div>');
}

$params = [':busca' => "%$q%"];
$sql = "SELECT l.id, l.titulo,
        COALESCE(NULLIF(l.capa_local, ''), l.capa_url) AS capa,
        taut.nome AS autor_nome,
        tedit.nome AS editora_nome
        FROM livros l
        LEFT JOIN tags taut ON l.autor_id = taut.id
        LEFT JOIN tags tedit ON l.editora_id = tedit.id
        WHERE l.titulo LIKE :busca 
           OR l.descricao LIKE :busca 
           OR taut.nome LIKE :busca 
           OR tedit.nome LIKE :busca
        ORDER BY l.criado_em DESC
        LIMIT 10";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$livros = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (count($livros) === 0) {
    echo '<div class="alert alert-info">Nenhum livro encontrado.</div>';
    exit;
}
?>

<div class="row row-cols-1 row-cols-md-3 g-4">
<?php foreach ($livros as $livro): ?>
  <div class="col">
    <div class="card h-100 shadow-sm">
      <?php if (!empty($livro['capa'])): ?>
        <img src="<?= htmlspecialchars($livro['capa']) ?>" class="card-img-top" style="height:220px; object-fit:cover;" alt="Capa do livro">
      <?php else: ?>
        <div class="bg-secondary text-white text-center py-5">Sem Capa</div>
      <?php endif; ?>
      <div class="card-body text-center">
        <h6 class="card-title"><?= htmlspecialchars($livro['titulo']) ?></h6>
        <p class="small text-muted"><?= htmlspecialchars($livro['autor_nome'] ?? '-') ?> | <?= htmlspecialchars($livro['editora_nome'] ?? '-') ?></p>
        <a href="<?= URL_BASE ?>frontend/usuario/livro.php?id=<?= $livro['id'] ?>" class="btn btn-sm btn-outline-primary" onclick="return verificarLoginOuRedirecionar();">
          <i class="bi bi-eye"></i> Ver Detalhes
        </a>
      </div>
    </div>
  </div>
<?php endforeach; ?>
</div>

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
