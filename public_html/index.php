<?php
// 🛠️ Exibir erros em ambiente de desenvolvimento
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// 🔄 Includes essenciais
require_once __DIR__ . '/../backend/config/config.php';     // ✅ ADICIONADO: define URL_BASE, NOME_SISTEMA, etc.
require_once __DIR__ . '/../backend/includes/db.php';
require_once __DIR__ . '/../backend/includes/session.php';

// ✅ Verifica conexão com PDO
if (!isset($pdo) || !$pdo) {
    die('<div class="alert alert-danger">Erro: conexão com o banco não estabelecida.</div>');
}
?>
<!DOCTYPE html>
<html lang="pt-br" data-tema="<?= htmlspecialchars($_COOKIE['modo_tema'] ?? 'claro') ?>">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= defined('NOME_SISTEMA') ? NOME_SISTEMA : 'Biblioteca CNI' ?> - Início</title>

  <!-- CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
  <link rel="stylesheet" href="<?= URL_BASE ?>frontend/assets/css/layout.css">
</head>
<body class="bg-light">

<div class="container py-5 text-center">
  <h1 class="fw-bold mb-2">📚 Biblioteca Comunitária CNI</h1>
  <p class="lead mb-4">Sistema de leitura, empréstimos e comunidade.</p>

  <div class="mb-5">
    <a href="<?= URL_BASE ?>frontend/login/login_user.php" class="btn btn-primary px-4 me-2">
      <i class="bi bi-person"></i> Entrar
    </a>
    <a href="<?= URL_BASE ?>frontend/login/register_user.php" class="btn btn-outline-success px-4">
      <i class="bi bi-person-plus"></i> Criar Conta
    </a>
  </div>

  <h4 class="mb-4">📚 Livros em Destaque</h4>
  <div class="row row-cols-1 row-cols-sm-2 row-cols-md-4 g-4">
    <?php
    try {
        $destaques = $pdo->query("SELECT titulo, capa, capa_url, isbn FROM livros ORDER BY RAND() LIMIT 4");

        while ($livro = $destaques->fetch(PDO::FETCH_ASSOC)):
    ?>
    <div class="col">
      <div class="card h-100 shadow-sm">
        <?php if (!empty($livro['capa'])): ?>
          <img src="<?= URL_BASE ?>uploads/capas/<?= htmlspecialchars($livro['capa']) ?>" alt="Capa do livro" class="card-img-top" style="height: 220px; object-fit: cover;">
        <?php elseif (!empty($livro['capa_url']) && filter_var($livro['capa_url'], FILTER_VALIDATE_URL)): ?>
          <img src="<?= htmlspecialchars($livro['capa_url']) ?>" alt="Capa do livro" class="card-img-top" style="height: 220px; object-fit: cover;">
        <?php else: ?>
          <div class="bg-secondary text-white text-center py-5">Sem Capa</div>
        <?php endif; ?>
        <div class="card-body text-center">
          <h6 class="card-title"><?= htmlspecialchars($livro['titulo']) ?></h6>
          <a href="<?= URL_BASE ?>frontend/usuario/livro.php?isbn=<?= urlencode($livro['isbn']) ?>" class="btn btn-sm btn-primary">
            <i class="bi bi-eye"></i> Ver
          </a>
        </div>
      </div>
    </div>
    <?php
        endwhile;
    } catch (PDOException $e) {
        echo '<div class="alert alert-danger">Erro ao carregar os livros em destaque.</div>';
        error_log('Erro SQL: ' . $e->getMessage());
    }
    ?>
  </div>
</div>

<?php require_once __DIR__ . '/../backend/includes/footer.php'; ?>

</body>
</html>
