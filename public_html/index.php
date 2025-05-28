<?php
define('BASE_PATH', dirname(__DIR__) . '/backend');
require_once BASE_PATH . '/config/config.php';
require_once BASE_PATH . '/includes/session.php';
require_once BASE_PATH . '/includes/header.php';

// 🔒 Garante que o usuário esteja logado
exigir_login('usuario');
?>

<div class="container py-5">
  <!-- 👋 Saudação -->
  <div class="row justify-content-between align-items-center mb-4">
    <div class="col-md-8">
      <h2 class="fw-bold">👋 Olá, <?= htmlspecialchars($_SESSION['usuario_nome']) ?></h2>
      <p class="text-muted">Bem-vindo à sua área da <strong>Biblioteca Comunitária CNI</strong>.</p>
    </div>
    <div class="col-md-4 text-end">
      <a href="<?= URL_BASE ?>logout.php" class="btn btn-outline-danger">
        <i class="bi bi-box-arrow-right"></i> Sair
      </a>
    </div>
  </div>

  <!-- 🔗 Links rápidos -->
  <div class="row g-4">
    <!-- 📚 Acesso aos livros -->
    <div class="col-md-4">
      <div class="card shadow-sm border-0 h-100">
        <div class="card-body text-center">
          <h5 class="card-title">📚 Meus Livros</h5>
          <p class="card-text">Veja seus livros lidos, favoritos e observações.</p>
          <a href="<?= URL_BASE ?>frontend/usuario/meus_livros.php" class="btn btn-primary w-100">Acessar</a>
        </div>
      </div>
    </div>

    <!-- 👤 Perfil do usuário -->
    <div class="col-md-4">
      <div class="card shadow-sm border-0 h-100">
        <div class="card-body text-center">
          <h5 class="card-title">👤 Meu Perfil</h5>
          <p class="card-text">Atualize seus dados, imagem e senha.</p>
          <a href="<?= URL_BASE ?>frontend/usuario/perfil.php" class="btn btn-secondary w-100">Acessar</a>
        </div>
      </div>
    </div>

    <!-- 💡 Sugestões -->
    <div class="col-md-4">
      <div class="card shadow-sm border-0 h-100">
        <div class="card-body text-center">
          <h5 class="card-title">💡 Sugestões</h5>
          <p class="card-text">Envie ideias de livros ou melhorias para o sistema.</p>
          <a href="<?= URL_BASE ?>frontend/usuario/sugestao.php" class="btn btn-outline-success w-100">Enviar</a>
        </div>
      </div>
    </div>
  </div>
</div>

<?php require_once BASE_PATH . '/includes/footer.php'; ?>
