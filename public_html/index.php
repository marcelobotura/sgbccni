<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/../backend/config/config.php';
require_once __DIR__ . '/../backend/includes/db.php';
require_once __DIR__ . '/../backend/includes/session.php';

$busca = trim($_GET['q'] ?? '');

function capaLivro($livro) {
  if (!empty($livro['capa_local']) && file_exists(BASE_PATH . '/storage/uploads/capas/' . $livro['capa_local'])) {
    return URL_BASE . 'storage/uploads/capas/' . $livro['capa_local'];
  }
  return $livro['capa_url'] ?? (URL_BASE . 'frontend/assets/img/livro_padrao.png');
}

// 🔎 Consulta de busca
$resultados = [];
if ($busca !== '') {
  $sqlBusca = "SELECT DISTINCT l.id, l.titulo, l.capa_url, l.capa_local, l.tipo
               FROM livros l
               LEFT JOIN tags t1 ON t1.id = l.autor_id
               LEFT JOIN tags t2 ON t2.id = l.editora_id
               LEFT JOIN tags t3 ON t3.id = l.categoria_id
               WHERE l.titulo LIKE :busca
                  OR t1.nome LIKE :busca
                  OR t2.nome LIKE :busca
                  OR t3.nome LIKE :busca
               ORDER BY l.criado_em DESC";
  $stmt = $pdo->prepare($sqlBusca);
  $stmt->execute([':busca' => "%$busca%"]);
  $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// 📚 Seções da home
$destaques = $pdo->query("SELECT id, titulo, capa_url, capa_local, tipo FROM livros WHERE destaque = 1 ORDER BY criado_em DESC LIMIT 10")->fetchAll(PDO::FETCH_ASSOC);
$novos = $pdo->query("SELECT id, titulo, capa_url, capa_local, tipo FROM livros ORDER BY criado_em DESC LIMIT 10")->fetchAll(PDO::FETCH_ASSOC);
$mais_lidos = $pdo->query("SELECT l.id, l.titulo, l.capa_url, l.capa_local, l.tipo, COUNT(lu.id) AS favoritos
                            FROM livros l
                            JOIN livros_usuarios lu ON l.id = lu.livro_id AND lu.status = 'favorito'
                            GROUP BY l.id ORDER BY favoritos DESC LIMIT 10")->fetchAll(PDO::FETCH_ASSOC);
$midias = $pdo->query("SELECT id, titulo, url, capa_url, tipo FROM midias ORDER BY criado_em DESC LIMIT 10")->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= NOME_SISTEMA ?> - Biblioteca CNI</title>
  <link rel="stylesheet" href="<?= URL_BASE ?>frontend/assets/css/base/base.css">
  <link rel="stylesheet" href="<?= URL_BASE ?>frontend/assets/css/pages/public.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css"/>
  <style>
    .livro-item { text-align: center; }
    .livro-item img { width: 100px; height: 140px; object-fit: cover; border-radius: 10px; transition: .3s; }
    .livro-item small { display: block; margin-top: 4px; font-size: .9rem; color: #333; }
    .tipo-badge { margin-top: 6px; display: inline-block; background: #6c757d; color: #fff; padding: 2px 8px; border-radius: 12px; font-size: 0.7rem; }
    .livro-item:hover img { transform: scale(1.05); }
    .btn-ver-mais { margin-top: 6px; font-size: 0.8rem; }
    .tema-toggle { cursor: pointer; font-size: 1.3rem; margin-left: 1rem; }
  </style>
</head>
<body data-bs-theme="light">
<header class="bg-dark text-white py-3 mb-4">
  <div class="container d-flex justify-content-between align-items-center">
    <h1 class="h4 m-0"><a href="<?= URL_BASE ?>index.php" class="text-white text-decoration-none">📚 <?= NOME_SISTEMA ?></a></h1>
    <nav class="d-flex align-items-center">
      <a href="<?= URL_BASE ?>index.php" class="text-white me-3">Início</a>
      <a href="<?= URL_BASE ?>sobre.php" class="text-white me-3">Sobre</a>
      <a href="<?= URL_BASE ?>sistema.php" class="text-white me-3">Sistema</a>
      <a href="<?= URL_BASE ?>contato.php" class="text-white me-3">Contato</a>
      <a href="<?= URL_BASE ?>login.php" class="btn btn-outline-light btn-sm">Entrar</a>
      <i class="bi bi-moon-stars-fill tema-toggle" id="tema-toggle"></i>
    </nav>
  </div>
</header>
<div class="container">
  <form method="GET" class="input-group mb-5 mx-auto form-busca-home" style="max-width:600px">
    <input type="text" name="q" class="form-control" placeholder="Buscar por título, autor ou categoria..." value="<?= htmlspecialchars($busca) ?>">
    <button class="btn btn-primary" type="submit"><i class="bi bi-search"></i></button>
  </form>

  <?php if ($busca !== ''): ?>
    <h5 class="mb-3">🔎 Resultados para: <em><?= htmlspecialchars($busca) ?></em></h5>
    <div class="row g-4">
      <?php foreach ($resultados as $livro): ?>
        <div class="col-6 col-sm-4 col-md-3 col-lg-2">
          <div class="livro-item">
            <a href="<?= URL_BASE ?>ver_livro.php?id=<?= $livro['id'] ?>">
              <img src="<?= capaLivro($livro) ?>" alt="Capa do livro">
              <?php if (!empty($livro['tipo'])): ?><div class="tipo-badge"><?= ucfirst($livro['tipo']) ?></div><?php endif; ?>
              <small><?= htmlspecialchars($livro['titulo']) ?></small>
            </a>
            <a href="<?= URL_BASE ?>ver_livro.php?id=<?= $livro['id'] ?>" class="btn btn-outline-primary btn-sm btn-ver-mais">Ver mais</a>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  <?php else:
    $secoes = [ '🏅 Destaques' => $destaques, '🆕 Novas Aquisições' => $novos, '⭐ Mais Lidos' => $mais_lidos ];
    foreach ($secoes as $titulo => $livros):
  ?>
    <h4><?= $titulo ?></h4>
    <div class="swiper mySwiper mb-4">
      <div class="swiper-wrapper">
        <?php foreach ($livros as $livro): ?>
          <div class="swiper-slide livro-item">
            <a href="<?= URL_BASE ?>ver_livro.php?id=<?= $livro['id'] ?>">
              <img src="<?= capaLivro($livro) ?>" alt="Livro">
              <?php if (!empty($livro['tipo'])): ?><div class="tipo-badge"><?= ucfirst($livro['tipo']) ?></div><?php endif; ?>
              <small><?= htmlspecialchars($livro['titulo']) ?></small>
            </a>
            <a href="<?= URL_BASE ?>ver_livro.php?id=<?= $livro['id'] ?>" class="btn btn-outline-primary btn-sm btn-ver-mais">Ver mais</a>
          </div>
        <?php endforeach; ?>
      </div>
    </div>
  <?php endforeach; ?>

  <h4>🎧 LivroCast e Vídeos</h4>
  <div class="swiper mySwiper mb-5">
    <div class="swiper-wrapper">
      <?php foreach ($midias as $midia): ?>
        <div class="swiper-slide livro-item">
          <a href="<?= htmlspecialchars($midia['url']) ?>" target="_blank">
            <img src="<?= !empty($midia['capa_url']) ? htmlspecialchars($midia['capa_url']) : URL_BASE . 'frontend/assets/img/livro_padrao.png' ?>" alt="<?= htmlspecialchars($midia['tipo']) ?>">
            <div class="tipo-badge"><?= ucfirst($midia['tipo']) ?></div>
            <small><?= htmlspecialchars($midia['titulo']) ?></small>
          </a>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
  <?php endif; ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
<script>
  const temaToggle = document.getElementById('tema-toggle');
  temaToggle.onclick = () => {
    const atual = document.body.getAttribute('data-bs-theme');
    const novo = atual === 'dark' ? 'light' : 'dark';
    document.body.setAttribute('data-bs-theme', novo);
    document.cookie = 'modo_tema=' + novo;
    temaToggle.classList.toggle('bi-sun-fill');
    temaToggle.classList.toggle('bi-moon-stars-fill');
  };

  new Swiper('.mySwiper', {
    slidesPerView: 2,
    spaceBetween: 20,
    breakpoints: {
      576: { slidesPerView: 3 },
      768: { slidesPerView: 4 },
      992: { slidesPerView: 5 },
      1200: { slidesPerView: 6 }
    },
    loop: true,
    autoplay: { delay: 3500 },
  });
</script>
</body>
</html>
