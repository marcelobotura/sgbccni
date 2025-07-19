<?php
// perfil.php - visualização moderna de perfil do usuário

define('BASE_PATH', dirname(__DIR__) . '/../backend');
require_once BASE_PATH . '/config/config.php';
require_once BASE_PATH . '/includes/session.php';
require_once BASE_PATH . '/includes/header.php';
require_once __DIR__ . '/protect_usuario.php';

exigir_login('usuario');

$nome = htmlspecialchars($_SESSION['usuario_nome'] ?? 'Nome indefinido');
$email = htmlspecialchars($_SESSION['usuario_email'] ?? 'sem_email@exemplo.com');
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
    <div class="card p-4 shadow-lg" style="max-width: 600px; width: 100%;">
      <div class="row align-items-center g-4">
        <div class="col-md-4 text-center">
          <?php if ($foto): ?>
            <img src="<?= URL_BASE . 'uploads/perfis/' . htmlspecialchars($foto) ?>"
                 alt="Foto de perfil"
                 class="rounded-circle shadow border border-2 border-primary"
                 style="width: 120px; height: 120px; object-fit: cover;">
          <?php else: ?>
            <div class="bg-light rounded-circle shadow d-flex align-items-center justify-content-center"
                 style="width: 120px; height: 120px;">
              <i class="bi bi-person-circle" style="font-size: 3rem;"></i>
            </div>
          <?php endif; ?>
        </div>

        <div class="col-md-8">
          <h4 class="fw-semibold mb-1"><?= $nome ?></h4>
          <p class="text-muted mb-1"><i class="bi bi-envelope"></i> <?= $email ?></p>
          <p class="text-muted mb-3"><i class="bi bi-calendar-event"></i> Início: <?= $data_formatada ?></p>

          <a href="editar_conta.php" class="btn btn-primario">
            <i class="bi bi-pencil"></i> Editar Conta
          </a>
        </div>
      </div>
    </div>
  </section>
</main>

<?php require_once BASE_PATH . '/includes/footer.php'; ?>
</body>
</html>
