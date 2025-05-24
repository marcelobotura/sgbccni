<?php if (!empty($_SESSION['erro'])): ?>
  <div class="alert alert-danger alert-dismissible fade show" role="alert">
    <?= htmlspecialchars($_SESSION['erro']); unset($_SESSION['erro']); ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fechar"></button>
  </div>
<?php elseif (!empty($_SESSION['sucesso'])): ?>
  <div class="alert alert-success alert-dismissible fade show" role="alert">
    <?= htmlspecialchars($_SESSION['sucesso']); unset($_SESSION['sucesso']); ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fechar"></button>
  </div>
<?php endif; ?>
