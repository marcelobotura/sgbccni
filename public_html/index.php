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
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
  <link rel="stylesheet" href="<?= URL_BASE ?>frontend/assets/css/estilo.css">
</head>
<body>
  <header class="navbar navbar-expand-lg navbar-dark bg-primary">
    <div class="container">
      <a class="navbar-brand fw-bold" href="#">📚 <?= NOME_SISTEMA ?></a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#menuPrincipal">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="menuPrincipal">
        <ul class="navbar-nav ms-auto">
          <li class="nav-item"><a href="index.php" class="nav-link">Início</a></li>
          <li class="nav-item"><a href="sobre.php" class="nav-link">Sobre</a></li>
          <li class="nav-item"><a href="sistema.php" class="nav-link">Sistema</a></li>
          <li class="nav-item"><a href="contato.php" class="nav-link">Contato</a></li>
          <li class="nav-item"><a href="<?= URL_BASE ?>frontend/login/login.php" class="btn btn-light ms-2">Entrar</a></li>
        </ul>
      </div>
    </div>
  </header>

  <main class="container py-5">
    <h2 class="text-center mb-4">Destaques da Biblioteca</h2>
    <div class="row justify-content-center g-3">
      <?php
      try {
        $stmt = $pdo->query("SELECT id, titulo, capa_local, capa_url FROM livros ORDER BY RAND() LIMIT 8");
        while ($livro = $stmt->fetch(PDO::FETCH_ASSOC)):
      ?>
        <div class="col-6 col-md-3">
          <div class="card h-100 livro-card">
            <?php if (!empty($livro['capa_local'])): ?>
              <img src="<?= URL_BASE ?>uploads/capas/<?= htmlspecialchars($livro['capa_local']) ?>" class="card-img-top" alt="Capa">
            <?php elseif (!empty($livro['capa_url'])): ?>
              <img src="<?= htmlspecialchars($livro['capa_url']) ?>" class="card-img-top" alt="Capa">
            <?php else: ?>
              <div class="sem-capa">Sem Capa</div>
            <?php endif; ?>
            <div class="card-body text-center">
              <h6 class="card-title"><?= htmlspecialchars($livro['titulo']) ?></h6>
              <a href="<?= URL_BASE ?>frontend/usuario/livro.php?id=<?= $livro['id'] ?>" class="btn btn-sm btn-outline-primary">Ver</a>
            </div>
          </div>
        </div>
      <?php endwhile; } catch (PDOException $e) {
        echo '<div class="alert alert-danger">Erro: ' . $e->getMessage() . '</div>';
      } ?>
    </div>

    <div class="input-group input-group-lg my-5">
      <input type="text" id="campo-busca" class="form-control" placeholder="Pesquisar por título, autor, editora...">
      <button class="btn btn-dark"><i class="bi bi-search"></i> Buscar</button>
    </div>

    <div id="resultados"></div>
  </main>

  <footer class="bg-dark text-white text-center py-3">
    <p class="mb-1">Sistema <strong><?= NOME_SISTEMA ?></strong> | Versão <?= VERSAO_SISTEMA ?></p>
    <p class="mb-0">Criado por Marcelo Botura Souza &copy; <?= date('Y') ?></p>
  </footer>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    document.getElementById('campo-busca').addEventListener('keyup', function () {
      const termo = this.value.trim();
      const resultados = document.getElementById('resultados');

      if (termo.length >= 2) {
        fetch('<?= URL_BASE ?>backend/controllers/ajax/buscar_livros.php?q=' + encodeURIComponent(termo))
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
</body>
</html>
