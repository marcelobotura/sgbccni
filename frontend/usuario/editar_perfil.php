<?php
// Caminho: frontend/usuario/editar_perfil.php

define('BASE_PATH', dirname(__DIR__, 2) . '/backend');
require_once BASE_PATH . '/config/config.php';
require_once BASE_PATH . '/includes/session.php';
require_once BASE_PATH . '/includes/header.php';
require_once __DIR__ . '/protect_usuario.php';

exigir_login('usuario');

// Buscar do banco, como em ver_perfil.php
$id_usuario = $_SESSION['usuario_id'] ?? null;
if (!$id_usuario) {
  header("Location: login.php");
  exit;
}

$stmt = $pdo->prepare("SELECT nome, email, foto FROM usuarios WHERE id = ?");
$stmt->execute([$id_usuario]);
$usuario = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$usuario) {
  $_SESSION['erro'] = "Usuário não encontrado.";
  header("Location: login.php");
  exit;
}

$nome  = htmlspecialchars($usuario['nome']);
$email = htmlspecialchars($usuario['email']);
$foto  = $usuario['foto'] ?? null;

// Caminhos corretos para imagem
$caminhoFisicoFoto = dirname(__DIR__, 2) . '/storage/uploads/perfis/' . $foto;
$caminhoWebFoto = URL_BASE . 'storage/uploads/perfis/' . $foto;

// Verifica se a imagem existe
$existeFoto = (!empty($foto) && file_exists($caminhoFisicoFoto));
$caminhoFoto = $existeFoto ? $caminhoWebFoto : URL_BASE . 'frontend/assets/img/perfil_sem_img.png';
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <title>Editar Perfil - <?= NOME_SISTEMA ?></title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
  <link rel="stylesheet" href="<?= URL_BASE ?>frontend/assets/css/base.css">
  <link rel="stylesheet" href="<?= URL_BASE ?>frontend/assets/css/layout.css">
  <link rel="stylesheet" href="<?= URL_BASE ?>frontend/assets/css/components.css">
</head>
<body>

<div class="container py-5">
  <h2 class="mb-4"><i class="bi bi-pencil"></i> Editar Perfil</h2>

  <?php if (!empty($_SESSION['erro'])): ?>
    <div class="alert alert-danger"><?= htmlspecialchars($_SESSION['erro']); unset($_SESSION['erro']); ?></div>
  <?php elseif (!empty($_SESSION['sucesso'])): ?>
    <div class="alert alert-success"><?= htmlspecialchars($_SESSION['sucesso']); unset($_SESSION['sucesso']); ?></div>
  <?php endif; ?>

  <form action="salvar_perfil.php" method="POST" enctype="multipart/form-data" class="card p-4 shadow-lg" id="formPerfil">
    <div class="row mb-4 align-items-center">
      <div class="col-md-3 text-center">
        <img src="<?= $caminhoFoto ?>" alt="Foto atual" id="previewFoto" class="rounded-circle shadow border" style="width: 120px; height: 120px; object-fit: cover;">
        <p class="text-muted mt-2 mb-0">Foto atual</p>

        <?php if ($existeFoto): ?>
          <a href="remover_foto.php" class="btn btn-sm btn-outline-danger mt-2" onclick="return confirm('Tem certeza que deseja remover sua foto de perfil?')">
            <i class="bi bi-trash"></i> Remover foto
          </a>
        <?php endif; ?>
      </div>

      <div class="col-md-9">
        <div class="mb-3">
          <label for="nome" class="form-label">Nome completo</label>
          <input type="text" class="form-control" id="nome" name="nome" value="<?= $nome ?>" required>
        </div>

        <div class="mb-3">
          <label for="email" class="form-label">E-mail</label>
          <input type="email" class="form-control" id="email" name="email" value="<?= $email ?>" required>
        </div>

        <div class="mb-3">
          <label for="nova_senha" class="form-label">Nova senha <small class="text-muted">(deixe em branco se não quiser alterar)</small></label>
          <input type="password" class="form-control" id="nova_senha" name="nova_senha" placeholder="********">
        </div>

        <div class="mb-3">
          <label for="foto" class="form-label">Nova foto de perfil <small class="text-muted">(jpg, png, até 2MB)</small></label>
          <input type="file" class="form-control" id="foto" name="foto" accept="image/png, image/jpeg">
          <div id="erroFoto" class="text-danger mt-1"></div>
        </div>
      </div>
    </div>

    <div class="d-flex justify-content-between">
      <a href="ver_perfil.php" class="btn btn-outline-secondary"><i class="bi bi-arrow-left"></i> Cancelar</a>
      <button type="submit" class="btn btn-success"><i class="bi bi-check-circle"></i> Salvar Alterações</button>
    </div>
  </form>
</div>

<script>
document.getElementById('foto').addEventListener('change', function (event) {
  const file = event.target.files[0];
  const erro = document.getElementById('erroFoto');
  const preview = document.getElementById('previewFoto');
  erro.textContent = '';

  if (!file) return;

  const validTypes = ['image/jpeg', 'image/png'];
  const maxSize = 2 * 1024 * 1024;

  if (!validTypes.includes(file.type)) {
    erro.textContent = 'Formato inválido. Use JPG ou PNG.';
    event.target.value = '';
    return;
  }

  if (file.size > maxSize) {
    erro.textContent = 'Imagem muito grande. Limite: 2MB.';
    event.target.value = '';
    return;
  }

  const reader = new FileReader();
  reader.onload = function (e) {
    preview.src = e.target.result;
  };
  reader.readAsDataURL(file);
});
</script>

</body>
</html>
