<?php if (session_status() === PHP_SESSION_NONE) session_start(); ?>

<?php
$modo = $_COOKIE['modo_tema'] ?? 'claro';
$iconeTema = match ($modo) {
    'dark' => 'bi bi-moon-stars',
    'medio' => 'bi bi-cloud-sun',
    default => 'bi bi-brightness-high'
};
$iconeLogout = in_array($modo, ['dark', 'medio']) ? 'text-light' : 'text-dark';

$tipoUsuario = $_SESSION['usuario_tipo'] ?? '';
?>

<nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow-sm">
  <div class="container-fluid">
    <a class="navbar-brand fw-bold" href="<?= URL_BASE ?>">
      <i class="bi bi-book-half me-2"></i>Biblioteca CNI
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#menuNav">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="menuNav">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <?php if ($tipoUsuario === 'usuario'): ?>
          <li class="nav-item">
            <a class="nav-link" href="<?= URL_BASE ?>../frontend/usuario/meus_livros.php">
              <i class="bi bi-journal-check me-1"></i>Meus Livros
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="<?= URL_BASE ?>../frontend/usuario/meus_favoritos.php">
              <i class="bi bi-star me-1"></i>Favoritos
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="<?= URL_BASE ?>../frontend/usuario/perfil.php">
              <i class="bi bi-person-circle me-1"></i>Perfil
            </a>
          </li>
        <?php elseif ($tipoUsuario === 'admin'): ?>
          <li class="nav-item">
            <a class="nav-link" href="<?= URL_BASE ?>frontend/admin/pages/index.php">
              <i class="bi bi-speedometer2 me-1"></i>Painel Admin
            </a>
          </li>
        <?php endif; ?>
      </ul>
      <div class="d-flex gap-2">
        <button onclick="alternarTema()" class="btn btn-light btn-sm" title="Alternar tema">
          <i class="<?= $iconeTema ?>"></i>
        </button>
        <a href="<?= URL_BASE ?>logout.php" class="btn btn-outline-light btn-sm">
          <i class="bi bi-box-arrow-right me-1 <?= $iconeLogout ?>"></i>Sair
        </a>
      </div>
    </div>
  </div>
</nav>
