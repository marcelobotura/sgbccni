<?php
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../includes/session.php';
include_once __DIR__ . '/../../includes/header.php';

exigir_login('usuario'); // ✅ Verifica sessão e tipo
?>

<div class="text-end mb-3">
  <a href="<?= URL_BASE ?>login/logout.php" class="btn btn-outline-danger btn-sm">
    <i class="bi bi-box-arrow-right"></i> Sair
  </a>
</div>

<div class="text-center mb-4">
  <h2 class="fw-bold"><i class="bi bi-person-circle text-info"></i> Olá, <?= htmlspecialchars($_SESSION['usuario_nome']) ?>!</h2>
  <p class="lead">Bem-vindo(a) à sua área de leitor(a) da Biblioteca CNI.</p>
</div>

<div class="row g-4 justify-content-center">
  <div class="col-md-3">
    <a href="<?= URL_BASE ?>usuario/perfil.php" class="btn btn-lg w-100 btn-outline-primary shadow">
      <i class="bi bi-person-lines-fill fs-4"></i><br>Meu Perfil
    </a>
  </div>
  <div class="col-md-3">
    <a href="<?= URL_BASE ?>usuario/livros.php" class="btn btn-lg w-100 btn-outline-success shadow">
      <i class="bi bi-book fs-4"></i><br>Meus Livros
    </a>
  </div>
  <div class="col-md-3">
    <a href="<?= URL_BASE ?>usuario/emprestimos.php" class="btn btn-lg w-100 btn-outline-warning shadow">
      <i class="bi bi-clock-history fs-4"></i><br>Histórico
    </a>
  </div>
</div>

<?php include_once __DIR__ . '/../../includes/footer.php'; ?>
