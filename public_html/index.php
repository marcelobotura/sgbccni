<?php
require_once __DIR__ . '/../backend/config/config.php';
require_once __DIR__ . '/../backend/includes/db.php';
require_once __DIR__ . '/../backend/includes/session.php';
?>
<!DOCTYPE html>
<html lang="pt-br" data-tema="<?= htmlspecialchars($_COOKIE['modo_tema'] ?? 'claro') ?>">
<head>
  <meta charset="UTF-8">
  <title><?= NOME_SISTEMA ?> - Início</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
  <link rel="stylesheet" href="<?= URL_BASE ?>frontend/assets/css/layout.css">
  <style>
    .carousel-container {
      overflow-x: auto;
      display: flex;
      gap: 1rem;
      padding-bottom: 0.5rem;
      scroll-behavior: smooth;
    }
    .carousel-container::-webkit-scrollbar {
      height: 6px;
    }
    .carousel-container::-webkit-scrollbar-thumb {
      background-color: #007bff;
      border-radius: 4px;
    }
    .carousel-item {
      flex: 0 0 auto;
      width: 180px;
    }
    .carousel-item img {
      height: 240px;
      object-fit: cover;
    }
    .livro-card img {
      height: 220px;
      object-fit: cover;
    }
    #resultados { display: none; }
  </style>
</head>
<body class="bg-light">

<div class="container py-4">
  <!-- 🔝 Cabeçalho -->
  <header class="d-flex flex-wrap justify-content-between align-items-center mb-4">
    <h2 class="fw-bold text-primary">📚 <?= NOME_SISTEMA ?></h2>
    <nav class="d-flex flex-wrap gap-2">
      <a href="index.php" class="btn btn-outline-primary"><i class="bi bi-house-door"></i> Início</a>
      <a href="sobre.php" class="btn btn-outline-secondary"><i class="bi bi-info-circle"></i> Sobre Nós</a>
      <a href="sistema.php" class="btn btn-outline-dark"><i class="bi bi-gear"></i> Sistema</a>
      <a href="contato.php" class="btn btn-outline-success"><i class="bi bi-envelope"></i> Contato</a>
      <a href="<?= URL_BASE ?>frontend/login/login_user.php" class="btn btn-primary">
        <i class="bi bi-box-arrow-in-right"></i> Entrar
      </a>
      <a href="<?= URL_BASE ?>frontend/login/register_user.php" class="btn btn-success">
        <i class="bi bi-person-plus"></i> Criar Conta
      </a>
    </nav>
  </header>

  <!-- ⭐ Livros em Destaque -->
  <h4 class="mb-3 text-secondary"><i class="bi bi-star-fill text-warning"></i> Livros em Destaque</h4>
  <div class="carousel-container mb-5">
    <?php
    try {
      $stmt = $pdo->query("SELECT titulo, capa, capa_url, id FROM livros ORDER BY RAND() LIMIT 10");
      while ($livro = $stmt->fetch(PDO::FETCH_ASSOC)):
    ?>
      <div class="card shadow-sm carousel-item">
        <?php if (!empty($livro['capa'])): ?>
          <img src="<?= URL_BASE ?>uploads/capas/<?= htmlspecialchars($livro['capa']) ?>" class="card-img-top" alt="Capa do livro">
        <?php elseif (!empty($livro['capa_url'])): ?>
          <img src="<?= htmlspecialchars($livro['capa_url']) ?>" class="card-img-top" alt="Capa do livro">
        <?php else: ?>
          <div class="bg-secondary text-white text-center py-5">Sem Capa</div>
        <?php endif; ?>
        <div class="card-body p-2 text-center">
          <h6 class="card-title small"><?= htmlspecialchars($livro['titulo']) ?></h6>
          <a href="<?= URL_BASE ?>frontend/usuario/livro.php?id=<?= $livro['id'] ?>" class="btn btn-sm btn-primary" onclick="return verificarLoginOuRedirecionar();">
            <i class="bi bi-eye"></i> Ver
          </a>
        </div>
      </div>
    <?php endwhile; } catch (PDOException $e) {
      echo '<div class="alert alert-danger">Erro ao carregar livros: ' . $e->getMessage() . '</div>';
    } ?>
  </div>

  <!-- 🔍 Pesquisa Dinâmica -->
  <div class="input-group input-group-lg shadow-sm mb-4">
    <input type="text" id="campo-busca" class="form-control" placeholder="Pesquisar livros por título, autor, editora...">
    <button class="btn btn-dark" type="button"><i class="bi bi-search"></i> Buscar</button>
  </div>

  <!-- 🔽 Resultados da busca -->
  <div id="resultados" class="mb-5"></div>
</div>

<!-- 🔐 Redirecionamento para login -->
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

<!-- 🔎 Busca Dinâmica com AJAX -->
<script>
document.getElementById('campo-busca').addEventListener('keyup', function() {
  const termo = this.value.trim();
  const resultados = document.getElementById('resultados');

  if (termo.length >= 2) {
    fetch('<?= URL_BASE ?>backend/ajax/buscar_livros.php?q=' + encodeURIComponent(termo))
      .then(res => res.text())
      .then(html => {
        resultados.innerHTML = html;
        resultados.style.display = 'block';
      });
  } else {
    resultados.innerHTML = '';
    resultados.style.display = 'none';
  }
});
</script>

<!-- 📎 Rodapé fixo -->
<footer>
  <p class="mb-1">Sistema: <strong><?= NOME_SISTEMA ?></strong> | Versão 1.0</p>
  <p class="mb-1">Criado por <strong>Marcelo Botura Souza</strong></p>
  <p class="mb-0 small">&copy; <?= date('Y') ?> Todos os direitos reservados.</p>
</footer>

</body>
</html>