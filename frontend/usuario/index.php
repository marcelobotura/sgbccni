<?php
// 🔧 Exibir erros para desenvolvimento (remover em produção)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// 🔒 Includes e variáveis
define('BASE_PATH', dirname(__DIR__, 2) . '/backend');
require_once BASE_PATH . '/config/config.php';
require_once BASE_PATH . '/includes/session.php';
require_once BASE_PATH . '/includes/header.php';

exigir_login('usuario');

$nome = htmlspecialchars($_SESSION['usuario_nome'] ?? 'Usuário');
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <title>Painel do Usuário - Biblioteca CNI</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- 🔠 Fontes e ícones -->
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
  <link rel="icon" type="image/png" href="<?= URL_BASE ?>assets/img/favicon.png">

  <!-- 🎨 Estilos -->
  <link rel="stylesheet" href="<?= URL_BASE ?>frontend/assets/css/base.css">
  <link rel="stylesheet" href="<?= URL_BASE ?>frontend/assets/css/layout.css">
  <link rel="stylesheet" href="<?= URL_BASE ?>frontend/assets/css/components.css">
  <link rel="stylesheet" href="<?= URL_BASE ?>frontend/assets/css/pages/painel_usuario.css">
  <link rel="stylesheet" href="<?= URL_BASE ?>frontend/assets/css/themes/light.css" id="theme-style">
</head>
<body>

<main class="painel-usuario container">
  <header class="painel-header d-flex justify-content-between align-items-center my-4">
    <div>
      <h2>Olá, <?= $nome ?> 👋</h2>
      <!-- E-mail removido aqui -->
    </div>
    <a href="<?= URL_BASE ?>backend/controllers/auth/logout.php" class="btn btn-erro">
      <i class="bi bi-box-arrow-right"></i> Sair
    </a>
  </header>

  <section class="painel-cards grid gap-4">

    <article class="card">
      <h3 class="card-title">📚 Pesquisar Livros</h3>
      <p>Explore o acervo da biblioteca.</p>
      <a href="busca.php" class="btn btn-primario">Buscar</a>
    </article>

    <article class="card">
      <h3 class="card-title">📖 Meus Empréstimos</h3>
      <p>Acompanhe os livros que você está lendo.</p>
      <a href="emprestimos.php" class="btn btn-primario">Visualizar</a>
    </article>

        <article class="card">
      <h3 class="card-title">⭐ Favoritos</h3>
      <p>Seus livros favoritos em um só lugar.</p>
      <a href="meus_favoritos.php" class="btn btn-primario">Ver Favoritos</a>
    </article>

    <article class="card">
      <h3 class="card-title">📊 Relatórios</h3>
      <p>Veja estatísticas da sua leitura.</p>
      <a href="relatorios.php" class="btn btn-secundario">Ver Relatórios</a>
    </article>

    <article class="card">
      <h3 class="card-title">⚙️ Conta</h3>
      <p>Atualize seus dados pessoais.</p>
      <a href="editar_conta.php" class="btn btn-secundario">
        <i class="bi bi-pencil"></i> Editar Conta
      </a>
    </article>

  </section>
</main>

<?php require_once BASE_PATH . '/includes/footer.php'; ?>
</body>
</html>
