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

$resultados = [];
$midias_encontradas = [];

if ($busca !== '') {
  $sqlLivros = "SELECT DISTINCT l.id, l.titulo, l.capa_url, l.capa_local, l.tipo
                FROM livros l
                LEFT JOIN tags t1 ON t1.id = l.autor_id
                LEFT JOIN tags t2 ON t2.id = l.editora_id
                LEFT JOIN tags t3 ON t3.id = l.categoria_id
                WHERE l.titulo LIKE :busca
                   OR t1.nome LIKE :busca
                   OR t2.nome LIKE :busca
                   OR t3.nome LIKE :busca
                ORDER BY l.criado_em DESC";
  $stmt = $pdo->prepare($sqlLivros);
  $stmt->execute([':busca' => "%$busca%"]);
  $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);

  $sqlMidias = "SELECT id, titulo, url, capa_url, tipo
                FROM midias
                WHERE titulo LIKE :busca OR tipo LIKE :busca
                ORDER BY criado_em DESC";
  $stmtMidias = $pdo->prepare($sqlMidias);
  $stmtMidias->execute([':busca' => "%$busca%"]);
  $midias_encontradas = $stmtMidias->fetchAll(PDO::FETCH_ASSOC);
}

$destaques = $pdo->query("SELECT id, titulo, capa_url, capa_local, tipo FROM livros WHERE destaque = 1 ORDER BY criado_em DESC LIMIT 10")->fetchAll(PDO::FETCH_ASSOC);
$novos = $pdo->query("SELECT id, titulo, capa_url, capa_local, tipo FROM livros ORDER BY criado_em DESC LIMIT 10")->fetchAll(PDO::FETCH_ASSOC);
$mais_lidos = $pdo->query("SELECT l.id, l.titulo, l.capa_url, l.capa_local, l.tipo, COUNT(lu.id) AS favoritos
                            FROM livros l
                            JOIN livros_usuarios lu ON l.id = lu.livro_id AND lu.status = 'favorito'
                            GROUP BY l.id ORDER BY favoritos DESC LIMIT 10")->fetchAll(PDO::FETCH_ASSOC);
$midias = $pdo->query("SELECT id, titulo, url, capa_url, tipo FROM midias ORDER BY criado_em DESC LIMIT 20")->fetchAll(PDO::FETCH_ASSOC);
?>

<!-- HTML abaixo pode ser o mesmo da sua estrutura atual -->
<!-- Adicione onde desejar renderizar os resultados -->

<?php if (!empty($midias_encontradas)): ?>
  <h4 class="mt-5">🎧 Mídias encontradas</h4>
  <div class="midias-container d-flex flex-wrap gap-4">
    <?php foreach ($midias_encontradas as $midia): ?>
      <div class="midia-item <?= strtolower($midia['tipo']) === 'youtube' ? 'horizontal' : 'quadrado' ?>">
        <a href="<?= htmlspecialchars($midia['url']) ?>" target="_blank" class="text-decoration-none d-block">
          <div class="midia-thumb position-relative">
            <img src="<?= !empty($midia['capa_url']) ? htmlspecialchars($midia['capa_url']) : URL_BASE . 'frontend/assets/img/livro_padrao.png' ?>" alt="<?= htmlspecialchars($midia['titulo']) ?>">
            <?php if (strtolower($midia['tipo']) === 'youtube'): ?>
              <div class="btn-play"><i class="bi bi-play-circle-fill"></i></div>
            <?php endif; ?>
          </div>
          <div class="midia-info">
            <div class="tipo-badge"><?= ucfirst($midia['tipo']) ?></div>
            <small class="d-block mb-2"><?= htmlspecialchars($midia['titulo']) ?></small>
            <a href="<?= htmlspecialchars($midia['url']) ?>" target="_blank" class="btn btn-outline-primary btn-sm">
              <?= strtolower($midia['tipo']) === 'youtube' ? 'Ver vídeo' : 'Ouvir agora' ?>
            </a>
          </div>
        </a>
      </div>
    <?php endforeach; ?>
  </div>
<?php endif; ?>



<!DOCTYPE html>
<html lang="pt-br" data-tema="<?= htmlspecialchars($_COOKIE['modo_tema'] ?? 'claro') ?>">
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
    .tema-toggle { cursor: pointer; font-size: 1.3rem; margin-left: 1rem; }
    .livro-item img { width: 100%; height: 180px; object-fit: cover; border-radius: 10px; transition: .3s; }
    .livro-item:hover img { transform: scale(1.03); }
    .tipo-badge { margin-top: 6px; display: inline-block; background: #6c757d; color: #fff; padding: 2px 8px; border-radius: 12px; font-size: 0.7rem; }
    .btn-ver-mais { margin-top: 6px; font-size: 0.8rem; }

    .resultados-lista {
      display: flex;
      flex-wrap: wrap;
      gap: 20px;
      justify-content: start;
    }

    .resultados-lista .livro-item {
      width: 180px;
    }

    .midias-container {
      justify-content: start;
    }

    .midia-item {
      background: #fff;
      border: 1px solid #ddd;
      border-radius: 10px;
      overflow: hidden;
      transition: transform 0.3s;
    }

    .midia-item:hover {
      transform: scale(1.02);
    }

    .midia-item img {
      width: 100%;
      object-fit: cover;
      display: block;
    }

    .midia-info {
      padding: 10px;
    }

    .midia-item.horizontal {
      display: flex;
      width: 100%;
      max-width: 750px;
      height: 160px;
    }

    .midia-item.horizontal .midia-thumb {
      flex: 0 0 260px;
      height: 100%;
      position: relative;
    }

    .midia-item.horizontal img {
      height: 100%;
    }

    .btn-play {
      position: absolute;
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%);
      font-size: 3rem;
      color: rgba(255, 255, 255, 0.9);
      text-shadow: 1px 1px 3px #000;
      pointer-events: none;
    }

    .midia-item.horizontal .midia-info {
      flex: 1;
      padding: 15px;
      display: flex;
      flex-direction: column;
      justify-content: center;
    }

    .midia-item.quadrado {
      width: 180px;
      flex: 0 0 auto;
    }

    .midia-item.quadrado img {
      height: 180px;
    }

    @media (max-width: 768px) {
      .midia-item.horizontal {
        flex-direction: column;
        height: auto;
        max-width: 100%;
      }

      .midia-item.horizontal .midia-thumb {
        height: 180px;
      }

      .midia-item.horizontal img {
        height: 100%;
      }

      .midia-item.horizontal .midia-info {
        padding: 10px;
        text-align: center;
      }
    }
  </style>
</head>
<body class="bg-light d-flex flex-column min-vh-100">
  <header class="bg-white shadow-sm py-3 mb-4">
    <div class="container d-flex justify-content-between align-items-center">
      <a href="index.php" class="text-decoration-none text-primary fs-4 fw-bold">
        📚 <?= NOME_SISTEMA ?>
      </a>
      <nav>
        <a href="index.php" class="btn btn-link">Início</a>
        <a href="sobre.php" class="btn btn-link">Sobre</a>
        <a href="post/index.php" class="btn btn-link">Postagens</a>
        <a href="sistema.php" class="btn btn-link">Sistema</a>
        <a href="contato.php" class="btn btn-link">Contato</a>
        <a href="login.php" class="btn btn-primary"><i class="bi bi-box-arrow-in-right"></i> Entrar</a>
        <i class="bi bi-moon-stars-fill tema-toggle ms-3" id="tema-toggle" title="Alternar tema"></i>
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
      <div class="resultados-lista">
        <?php foreach ($resultados as $livro): ?>
          <div class="livro-item text-center">
            <a href="<?= URL_BASE ?>ver_livro.php?id=<?= $livro['id'] ?>">
              <img src="<?= capaLivro($livro) ?>" alt="Capa do livro">
              <?php if (!empty($livro['tipo'])): ?><div class="tipo-badge"><?= ucfirst($livro['tipo']) ?></div><?php endif; ?>
              <small><?= htmlspecialchars($livro['titulo']) ?></small>
            </a>
            <a href="<?= URL_BASE ?>ver_livro.php?id=<?= $livro['id'] ?>" class="btn btn-outline-primary btn-sm btn-ver-mais">Ver mais</a>
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
            <div class="swiper-slide livro-item text-center">
              <a href="ver_livro.php?id=<?= $livro['id'] ?>">
                <img src="<?= capaLivro($livro) ?>" alt="Livro">
                <?php if (!empty($livro['tipo'])): ?><div class="tipo-badge"><?= ucfirst($livro['tipo']) ?></div><?php endif; ?>
                <small><?= htmlspecialchars($livro['titulo']) ?></small>
              </a>
              <a href="ver_livro.php?id=<?= $livro['id'] ?>" class="btn btn-outline-primary btn-sm btn-ver-mais">Ver mais</a>
            </div>
          <?php endforeach; ?>
        </div>
      </div>
    <?php endforeach; ?>

    <!-- Seção de mídias -->
    <h4 class="mt-4">🎥 Vídeos</h4>
    <div class="midias-container mb-5 d-flex flex-wrap gap-4">
      <?php foreach ($midias as $midia): ?>
        <?php if (strtolower($midia['tipo']) === 'vídeo'): ?>
          <div class="midia-item horizontal">
            <a href="<?= htmlspecialchars($midia['url']) ?>" target="_blank" class="d-flex text-decoration-none">
              <div class="midia-thumb position-relative">
                <img src="<?= !empty($midia['capa_url']) ? htmlspecialchars($midia['capa_url']) : URL_BASE . 'frontend/assets/img/livro_padrao.png' ?>" alt="<?= htmlspecialchars($midia['titulo']) ?>">
                <div class="btn-play"><i class="bi bi-play-circle-fill"></i></div>
              </div>
              <div class="midia-info">
                <div class="tipo-badge"><?= ucfirst($midia['tipo']) ?></div>
                <small class="d-block mb-2"><?= htmlspecialchars($midia['titulo']) ?></small>
                <a href="<?= htmlspecialchars($midia['url']) ?>" target="_blank" class="btn btn-outline-primary btn-sm">Ver vídeo</a>
              </div>
            </a>
          </div>
        <?php endif; ?>
      <?php endforeach; ?>
    </div>

    <h4 class="mt-4">🎙️ Podcasts e Músicas</h4>
    <div class="midias-container mb-5 d-flex flex-wrap gap-4">
      <?php foreach ($midias as $midia): ?>
        <?php if (strtolower($midia['tipo']) !== 'vídeo'): ?>
          <div class="midia-item quadrado text-center">
            <a href="<?= htmlspecialchars($midia['url']) ?>" target="_blank" class="text-decoration-none">
              <div class="midia-thumb">
                <img src="<?= !empty($midia['capa_url']) ? htmlspecialchars($midia['capa_url']) : URL_BASE . 'frontend/assets/img/livro_padrao.png' ?>" alt="<?= htmlspecialchars($midia['titulo']) ?>">
              </div>
              <div class="midia-info">
                <div class="tipo-badge"><?= ucfirst($midia['tipo']) ?></div>
                <small class="d-block mb-2"><?= htmlspecialchars($midia['titulo']) ?></small>
                <a href="<?= htmlspecialchars($midia['url']) ?>" target="_blank" class="btn btn-outline-primary btn-sm">Ver vídeo</a>
              </div>
            </a>
          </div>
        <?php endif; ?>
      <?php endforeach; ?>
    </div>
    <?php endif; ?>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
  <script>
    document.getElementById('tema-toggle')?.addEventListener('click', () => {
      const html = document.documentElement;
      const temaAtual = html.getAttribute('data-tema') === 'escuro' ? 'claro' : 'escuro';
      html.setAttribute('data-tema', temaAtual);
      document.cookie = `modo_tema=${temaAtual};path=/;max-age=31536000`;
    });

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
