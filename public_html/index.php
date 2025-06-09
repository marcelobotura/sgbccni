<?php
require_once __DIR__ . '/../backend/config/config.php';
require_once __DIR__ . '/../backend/includes/session.php';

if (usuario_logado()) {
    if (tipo_usuario() === 'admin') {
        header('Location: ' . URL_BASE . 'frontend/admin/dashboard.php');
        exit;
    } elseif (tipo_usuario() === 'usuario') {
        header('Location: ' . URL_BASE . 'frontend/usuario/dashboard.php');
        exit;
    }
}

?>
<!DOCTYPE html>
<html lang="pt-br" data-tema="<?= htmlspecialchars($_COOKIE['modo_tema'] ?? 'claro') ?>">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= NOME_SISTEMA ?> - Início</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
  <link rel="stylesheet" href="<?= URL_BASE ?>frontend/assets/css/layout.css">
  
</head>
<body class="bg-light">

<div class="container py-5 text-center">
  <h1 class="fw-bold mb-2">📚 Biblioteca Comunitária CNI</h1>
  <p class="lead">Sistema de leitura, empréstimos e comunidade.</p>
  <div class="mb-4">
    <a href="<?= URL_BASE ?>login.php" class="btn btn-primary px-4">Entrar</a>
    <a href="<?= URL_BASE ?>/../../frontend/login/register_usuario.php" class="btn btn-outline-success px-4 ms-2">Criar Conta</a>
  </div>

  <div class="my-5">
    <h4 class="mb-3">📱 Acesse pelo QR Code</h4>
    <img src="<?= URL_BASE ?>/../../frontend/assets/img/qr_biblioteca_home.png" alt="QR Code da Biblioteca CNI" width="180" class="shadow">
  </div>

  <h4 class="text-center mb-4">📚 Livros em Destaque</h4>
  <div class="row row-cols-1 row-cols-md-4 g-4">
    <?php
    $destaques = $conn->query("SELECT titulo, capa_url, isbn FROM livros ORDER BY RAND() LIMIT 4");
    while ($livro = $destaques->fetch_assoc()):
    ?>
      <div class="col">
        <div class="card h-100 shadow-sm">
          <?php if ($livro['capa_url']): ?>
            <img src="<?= htmlspecialchars($livro['capa_url']) ?>" class="card-img-top" style="height: 220px; object-fit: cover;">
          <?php endif; ?>
          <div class="card-body text-center">
            <h6 class="card-title"><?= htmlspecialchars($livro['titulo']) ?></h6>
            <a href="<?= URL_BASE ?>livro.php?isbn=<?= urlencode($livro['isbn']) ?>" class="btn btn-sm btn-primary">Ver</a>
          </div>
        </div>
      </div>
    <?php endwhile; ?>
  </div>
</div>

<?php require_once __DIR__ . '/../backend/includes/footer.php'; ?>
</body>
</html>
