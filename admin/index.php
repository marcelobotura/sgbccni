<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../includes/session.php';
include_once __DIR__ . '/../includes/header.php';

exigir_login('admin'); // ✅ exige que seja administrador
?>

<div class="text-end mb-3">
  <a href="<?= URL_BASE ?>login/logout.php" class="btn btn-outline-danger btn-sm">
    <i class="bi bi-box-arrow-right"></i> Sair
  </a>
</div>

<div class="text-center mb-4">
  <h2 class="fw-bold"><i class="bi bi-shield-lock-fill text-purple"></i> Painel Administrativo</h2>
  <p class="lead">Bem-vindo(a), <strong><?= htmlspecialchars($_SESSION['usuario_nome']) ?></strong></p>
</div>

<div class="row g-4 justify-content-center">
  <div class="col-md-3">
    <a href="<?= URL_BASE ?>admin/pages/cadastrar_livro.php" class="btn btn-lg w-100 btn-outline-primary shadow">
      <i class="bi bi-journal-plus fs-4"></i><br>Cadastrar Livro
    </a>
  </div>
  <div class="col-md-3">
    <a href="<?= URL_BASE ?>admin/pages/usuarios.php" class="btn btn-lg w-100 btn-outline-info shadow">
      <i class="bi bi-people-fill fs-4"></i><br>Gerenciar Usuários
    </a>
  </div>
  <div class="col-md-3">
    <a href="<?= URL_BASE ?>admin/pages/emprestimos.php" class="btn btn-lg w-100 btn-outline-warning shadow">
      <i class="bi bi-book-half fs-4"></i><br>Controle de Empréstimos
    </a>
  </div>
</div>

<?php include_once __DIR__ . '/../includes/footer.php'; ?>
