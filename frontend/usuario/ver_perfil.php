<?php
// Caminho: frontend/usuario/ver_perfil.php

define('BASE_PATH', dirname(__DIR__, 2) . '/backend');
require_once BASE_PATH . '/config/config.php';
require_once BASE_PATH . '/includes/session.php';
require_once BASE_PATH . '/includes/header.php';
require_once __DIR__ . '/protect_usuario.php';

// 🔒 Verifica login do tipo usuário
exigir_login('usuario');

// 👤 Dados do usuário logado
$nome          = htmlspecialchars($_SESSION['usuario_nome'] ?? 'Usuário');
$email         = htmlspecialchars($_SESSION['usuario_email'] ?? 'sem_email@exemplo.com');
$foto          = $_SESSION['usuario_foto'] ?? null;
$data_inicio   = $_SESSION['usuario_data'] ?? null;
$data_formatada = $data_inicio ? date('d/m/Y', strtotime($data_inicio)) : 'Desconhecida';

// 📸 Caminho da imagem com verificação física no diretório correto
$caminhoCompleto  = dirname(__DIR__, 2) . '/storage/uploads/perfis/' . $foto;
$caminhoRelativo  = 'storage/uploads/perfis/' . $foto;
$caminhoFoto      = (!empty($foto) && file_exists($caminhoCompleto))
  ? URL_BASE . $caminhoRelativo
  : URL_BASE . 'frontend/assets/img/perfil_sem_img.png';
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <title>Meu Perfil - <?= NOME_SISTEMA ?></title>
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- Estilos -->
  <link rel="stylesheet" href="<?= URL_BASE ?>frontend/assets/css/base.css">
  <link rel="stylesheet" href="<?= URL_BASE ?>frontend/assets/css/layout.css">
  <link rel="stylesheet" href="<?= URL_BASE ?>frontend/assets/css/components.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body>

<main class="container py-5">
  <header class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="bi bi-person-circle me-2"></i> Meu Perfil</h2>
    <a href="index.php" class="btn btn-outline-secondary"><i class="bi bi-arrow-left"></i> Voltar</a>
  </header>

  <section class="d-flex justify-content-center">
    <div class="card p-4 shadow-lg" style="max-width: 600px; width: 100%;">
      <div class="row g-4 align-items-center">
        <div class="col-md-4 text-center">
          <img src="<?= $caminhoFoto ?>" alt="Foto do usuário" class="rounded-circle border border-2 shadow-sm" style="width: 120px; height: 120px; object-fit: cover;">
        </div>
        <div class="col-md-8">
          <h4 class="mb-1"><?= $nome ?></h4>
          <p class="text-muted mb-1"><i class="bi bi-envelope"></i> <?= $email ?></p>
          <p class="text-muted"><i class="bi bi-calendar-check"></i> Desde: <?= $data_formatada ?></p>

          <div class="d-flex flex-wrap gap-2 mt-3">
            <a href="editar_perfil.php" class="btn btn-primario"><i class="bi bi-pencil-square"></i> Editar Conta</a>
            <a href="excluir_conta.php" class="btn btn-outline-danger"><i class="bi bi-trash"></i> Excluir Conta</a>
          </div>
        </div>
      </div>
    </div>
  </section>
</main>

</body>
</html>
