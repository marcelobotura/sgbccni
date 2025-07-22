<?php
require_once __DIR__ . '/../backend/config/config.php';
require_once __DIR__ . '/../backend/includes/db.php';
require_once __DIR__ . '/../backend/includes/session.php';
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= NOME_SISTEMA ?> - Biblioteca Digital</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
  <style>
    body {
      background: #f9f9f9;
    }
    .secao-livros {
      margin-bottom: 3rem;
    }
    .secao-livros h3 {
      font-weight: bold;
      margin-bottom: 1rem;
    }
    .livros-scroll {
      display: flex;
      overflow-x: auto;
      gap: 1rem;
      padding-bottom: 0.5rem;
    }
    .livros-scroll::-webkit-scrollbar {
      height: 6px;
    }
    .livros-scroll::-webkit-scrollbar-thumb {
      background: #0d6efd;
      border-radius: 10px;
    }
    .livro-card {
      min-width: 140px;
      max-width: 140px;
      flex: 0 0 auto;
      background: white;
      border: 1px solid #ddd;
      border-radius: 6px;
      overflow: hidden;
      box-shadow: 0 1px 3px rgba(0,0,0,0.1);
    }
    .livro-card img {
      width: 100%;
      height: 200px;
      object-fit: cover;
    }
    .livro-card .titulo {
      font-size: 0.85rem;
      padding: 0.5rem;
      text-align: center;
    }
    .livro-card .btn-ver {
      display: block;
      margin: 0 auto 0.5rem;
    }
  </style>
</head>
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
          <li class="nav-item"><a href="catalago.php" class="nav-link">Catálogo</a></li>
          <li class="nav-item"><a href="contato.php" class="nav-link">Contato</a></li>
          <li class="nav-item"><a href="<?= URL_BASE ?>frontend/login/login.php" class="btn btn-light ms-2">Entrar</a></li>
        </ul>
      </div>
    </div>
  </header>
<body>


  <div class="container py-4">
   

    <?php
    // Exemplo com 3 seções fixas, cada uma com 8 livros
    $secoes = [
      'Livros em Destaque' => "ORDER BY RAND() LIMIT 8",
      'Novidades' => "ORDER BY criado_em DESC LIMIT 8",
      'Mais Lidos' => "ORDER BY acessos DESC LIMIT 8"
    ];

    foreach ($secoes as $titulo => $criterio):
      $stmt = $pdo->prepare("SELECT id, titulo, capa_local, capa_url FROM livros $criterio");
      $stmt->execute();
      $livros = $stmt->fetchAll(PDO::FETCH_ASSOC);
      if (!$livros) continue;
    ?>
    <div class="secao-livros">
      <h3><?= $titulo ?></h3>
      <div class="livros-scroll">
        <?php foreach ($livros as $livro): 
          $capa = (!empty($livro['capa_local']) && file_exists(__DIR__ . '/../storage/uploads/capas/' . $livro['capa_local']))
                  ? URL_BASE . 'storage/uploads/capas/' . $livro['capa_local']
                  : (!empty($livro['capa_url']) ? $livro['capa_url'] : URL_BASE . 'storage/uploads/capas/sem-capa.png');
        ?>
        <div class="livro-card">
          <a href="<?= URL_BASE ?>frontend/usuario/livro.php?id=<?= $livro['id'] ?>">
            <img src="<?= $capa ?>" alt="Capa do livro">
          </a>
          <div class="titulo"><?= htmlspecialchars($livro['titulo']) ?></div>
          <a href="<?= URL_BASE ?>frontend/usuario/livro.php?id=<?= $livro['id'] ?>" class="btn btn-sm btn-outline-primary btn-ver">
            <i class="bi bi-eye"></i> Ver
          </a>
        </div>
        <?php endforeach; ?>
      </div>
    </div>
    <?php endforeach; ?>

  </div>

  <footer class="bg-dark text-white text-center py-3">
    <p class="mb-0">&copy; <?= date('Y') ?> <?= NOME_SISTEMA ?> | Desenvolvido por Marcelo Botura Souza</p>
  </footer>
</body>
</html>
