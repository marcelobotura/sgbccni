<?php
// Caminho: frontend/usuario/ver_perfil.php

define('BASE_PATH', dirname(__DIR__, 2) . '/backend');
require_once BASE_PATH . '/config/config.php';
require_once BASE_PATH . '/includes/session.php';
require_once BASE_PATH . '/includes/header.php';
require_once __DIR__ . '/protect_usuario.php';

exigir_login('usuario');

// 🔐 Verifica e busca os dados mais recentes do banco de dados
$id_usuario = $_SESSION['usuario_id'] ?? null;
if (!$id_usuario) {
  header("Location: login.php");
  exit;
}

$stmt = $pdo->prepare("SELECT nome, email, foto, data_criacao FROM usuarios WHERE id = ?");
$stmt->execute([$id_usuario]);
$usuario = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$usuario) {
  $_SESSION['erro'] = "Usuário não encontrado.";
  header("Location: login.php");
  exit;
}

// 👤 Dados do usuário
$nome          = htmlspecialchars($usuario['nome']);
$email         = htmlspecialchars($usuario['email']);
$foto          = $usuario['foto'] ?? null;
$data_inicio   = $usuario['data_criacao'] ?? null;
$data_formatada = $data_inicio ? date('d/m/Y', strtotime($data_inicio)) : 'Desconhecida';

// 📸 Caminho da foto
$caminhoFisico  = dirname(__DIR__, 2) . '/storage/uploads/perfis/' . $foto;
$caminhoWeb     = URL_BASE . 'storage/uploads/perfis/' . $foto;
$caminhoFoto    = (!empty($foto) && file_exists($caminhoFisico)) ? $caminhoWeb : URL_BASE . 'frontend/assets/img/perfil_sem_img.png';
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <title>Meu Perfil - <?= NOME_SISTEMA ?></title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="<?= URL_BASE ?>frontend/assets/css/base.css">
  <link rel="stylesheet" href="<?= URL_BASE ?>frontend/assets/css/layout.css">
  <link rel="stylesheet" href="<?= URL_BASE ?>frontend/assets/css/components.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
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
          <img src="<?= $caminhoFoto ?>" alt="Foto do usuário" class="rounded-circle border shadow" style="width: 120px; height: 120px; object-fit: cover;">
        </div>
        <div class="col-md-8">
          <h4 class="mb-1"><?= $nome ?></h4>
          <p class="text-muted mb-1"><i class="bi bi-envelope"></i> <?= $email ?></p>
          <p class="text-muted"><i class="bi bi-calendar-check"></i> Desde: <?= $data_formatada ?></p>

          <div class="d-flex flex-wrap gap-2 mt-3">
            <a href="editar_perfil.php" class="btn btn-primary"><i class="bi bi-pencil-square"></i> Editar Conta</a>
            <a href="excluir_conta.php" class="btn btn-outline-danger" onclick="return confirm('Tem certeza que deseja excluir sua conta?')">
              <i class="bi bi-trash"></i> Excluir Conta
            </a>
          </div>
        </div>
      </div>
    </div>
  </section>
</main>

</body>
</html>
