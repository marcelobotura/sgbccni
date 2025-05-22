<?php
// Garante que config esteja carregado
if (!defined('URL_BASE')) {
  require_once __DIR__ . '/../config/config.php';
}

// Sessão já iniciada em session.php
$nome = $_SESSION['usuario_nome'] ?? 'Visitante';
$tipo = $_SESSION['usuario_tipo'] ?? null;
?>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark px-3" role="navigation" aria-label="Menu principal">
  <a class="navbar-brand fw-bold" href="<?= URL_BASE ?>">
    <i class="bi bi-book-half"></i>
    <?= htmlspecialchars(defined('NOME_SISTEMA') ? NOME_SISTEMA : 'Biblioteca CNI') ?>
  </a>

  <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#menuNav" aria-controls="menuNav" aria-expanded="false" aria-label="Alternar navegação">
    <span class="navbar-toggler-icon"></span>
  </button>

  <div class="collapse navbar-collapse" id="menuNav">
    <ul class="navbar-nav me-auto mb-2 mb-lg-0">
      <?php if ($tipo === 'admin'): ?>
        <li class="nav-item"><a class="nav-link" href="<?= URL_BASE ?>admin/">Painel Admin</a></li>
        <li class="nav-item"><a class="nav-link" href="<?= URL_BASE ?>admin/pages/usuarios.php">Usuários</a></li>
        <li class="nav-item"><a class="nav-link" href="<?= URL_BASE ?>admin/pages/cadastrar_livro.php">Cadastrar Livro</a></li>

      <?php elseif ($tipo === 'usuario'): ?>
        <li class="nav-item"><a class="nav-link" href="<?= URL_BASE ?>usuario/">Minha Área</a></li>
        <li class="nav-item"><a class="nav-link" href="<?= URL_BASE ?>usuario/perfil.php">Meu Perfil</a></li>
        <li class="nav-item"><a class="nav-link" href="<?= URL_BASE ?>usuario/livros.php">Meus Livros</a></li>

      <?php else: ?>
        <li class="nav-item"><a class="nav-link" href="<?= URL_BASE ?>login/index.php">Entrar</a></li>
        <li class="nav-item"><a class="nav-link" href="<?= URL_BASE ?>login/register.php">Cadastrar</a></li>
      <?php endif; ?>
    </ul>

    <div class="d-flex align-items-center text-white">
      <span class="me-3"><i class="bi bi-person-circle"></i> <?= htmlspecialchars($nome) ?></span>

      <?php if ($tipo): ?>
        <a href="<?= URL_BASE ?>login/logout.php" class="btn btn-sm btn-outline-light" aria-label="Sair do sistema">
          <i class="bi bi-box-arrow-right"></i> Sair
        </a>
      <?php endif; ?>
    </div>
  </div>
</nav>
