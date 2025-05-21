<?php
// Garante que sessão já esteja ativa via session.php
$nome = $_SESSION['usuario_nome'] ?? 'Visitante';
$tipo = $_SESSION['usuario_tipo'] ?? null;
?>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark px-3">
  <a class="navbar-brand fw-bold" href="<?= URL_BASE ?>">
    <i class="bi bi-book-half"></i>
    <?= defined('NOME_SISTEMA') ? NOME_SISTEMA : 'Biblioteca CNI' ?>
  </a>

  <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#menuNav">
    <span class="navbar-toggler-icon"></span>
  </button>

  <div class="collapse navbar-collapse" id="menuNav">
    <ul class="navbar-nav me-auto mb-2 mb-lg-0">
      <?php if ($tipo === 'admin'): ?>
        <li class="nav-item">
          <a class="nav-link" href="<?= URL_BASE ?>admin/">Painel Admin</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="<?= URL_BASE ?>admin/pages/usuarios.php">Usuários</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="<?= URL_BASE ?>admin/pages/cadastrar_livro.php">Cadastrar Livro</a>
        </li>
      <?php elseif ($tipo === 'usuario'): ?>
        <li class="nav-item">
          <a class="nav-link" href="<?= URL_BASE ?>usuario/">Minha Área</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="<?= URL_BASE ?>usuario/perfil.php">Meu Perfil</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="<?= URL_BASE ?>usuario/livros.php">Meus Livros</a>
        </li>
      <?php endif; ?>
    </ul>

    <div class="d-flex align-items-center text-white">
      <span class="me-3"><i class="bi bi-person-circle"></i> <?= htmlspecialchars($nome) ?></span>
      <a href="<?= URL_BASE ?>login/logout.php" class="btn btn-sm btn-outline-light">
        <i class="bi bi-box-arrow-right"></i> Sair
      </a>
    </div>
  </div>
</nav>
