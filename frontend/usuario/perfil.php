<?php
define('BASE_PATH', dirname(__DIR__) . '/../backend');
require_once BASE_PATH . '/config/config.php';
require_once BASE_PATH . '/includes/session.php';
require_once BASE_PATH . '/includes/header.php';
require_once 'protect_usuario.php';

exigir_login('usuario');

// Dados da sessão
$nome = htmlspecialchars($_SESSION['usuario_nome'] ?? '');
$email = htmlspecialchars($_SESSION['usuario_email'] ?? '');
$foto = $_SESSION['usuario_foto'] ?? null;
$data_inicio = $_SESSION['usuario_data'] ?? null;
$data_formatada = $data_inicio ? date('d/m/Y', strtotime($data_inicio)) : 'Desconhecida';
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <title>Meu Perfil - Biblioteca CNI</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- Estilos -->
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
  <link rel="stylesheet" href="<?= URL_BASE ?>frontend/assets/css/base.css">
  <link rel="stylesheet" href="<?= URL_BASE ?>frontend/assets/css/layout.css">
  <link rel="stylesheet" href="<?= URL_BASE ?>frontend/assets/css/components.css">
  <link rel="stylesheet" href="<?= URL_BASE ?>frontend/assets/css/pages/painel_usuario.css">
  <link rel="stylesheet" href="<?= URL_BASE ?>frontend/assets/css/themes/light.css" id="theme-style">
</head>
<body>

<main class="painel-usuario container">
  <header class="painel-header d-flex justify-content-between align-items-center mb-4">
    <h2><i class="bi bi-person-circle"></i> Meu Perfil</h2>
    <a href="index.php" class="btn btn-secundario"><i class="bi bi-arrow-left"></i> Voltar</a>
  </header>

  <section class="d-flex justify-content-center">
    <div class="card text-center p-4" style="max-width: 420px; width: 100%;">
      <?php if ($foto): ?>
        <img src="<?= URL_BASE . 'uploads/perfis/' . htmlspecialchars($foto) ?>"
             alt="Foto de perfil"
             class="rounded-circle shadow mb-3"
             style="width: 130px; height: 130px; object-fit: cover; cursor: pointer; display: block; margin: 0 auto; border: 5px solid #d60000; box-sizing: border-box;"
             data-bs-toggle="modal" data-bs-target="#fotoModal">
      <?php else: ?>
        <div class="bg-light rounded-circle shadow d-inline-flex align-items-center justify-content-center mb-3"
             style="width: 130px; height: 130px;">
          <i class="bi bi-person-circle" style="font-size: 3rem;"></i>
        </div>
      <?php endif; ?>

      <h4 class="mt-2"><?= $nome ?></h4>

      <p class="text-muted mb-1">
        <i class="bi bi-envelope"></i> <?= $email ?>
      </p>

      <p class="text-muted">
        <i class="bi bi-calendar-event"></i> Início no sistema: <?= $data_formatada ?>
      </p>

      <a href="editar_conta.php" class="btn btn-primario mt-3">
        <i class="bi bi-pencil"></i> Editar Conta
      </a>
    </div>
  </section>
</main>

<!-- Modal para ver a foto ampliada -->
<?php if ($foto): ?>
<div class="modal fade" id="fotoModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content bg-light">
      <div class="modal-body text-center">
        <img src="<?= URL_BASE . 'uploads/perfis/' . htmlspecialchars($foto) ?>" class="img-fluid rounded shadow" alt="Foto ampliada">
      </div>
    </div>
  </div>
</div>
<?php endif; ?>

<?php require_once BASE_PATH . '/includes/footer.php'; ?>
</body>
</html>
