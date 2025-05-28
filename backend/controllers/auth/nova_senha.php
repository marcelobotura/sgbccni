<?php
require_once __DIR__ . '/../../config/env.php'; // ✅ Garante que URL_BASE e constantes estão carregadas

$token = $_GET['token'] ?? '';
if (!$token) {
    echo "<p style='color: red;'>Token de redefinição ausente.</p>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <title>Nova Senha - <?= NOME_SISTEMA ?></title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container py-5">
  <div class="row justify-content-center">
    <div class="col-md-5">
      <div class="card shadow-sm border-0">
        <div class="card-body">
          <h4 class="mb-4 text-center">🔐 Definir Nova Senha</h4>

          <!-- 🔔 Exibe erros da sessão, se houver -->
          <?php session_start(); if (!empty($_SESSION['erro'])): ?>
            <div class="alert alert-danger"><?= $_SESSION['erro']; unset($_SESSION['erro']); ?></div>
          <?php endif; ?>

          <form action="salvar_nova_senha.php?token=<?= urlencode($token) ?>" method="POST" autocomplete="off">
            <div class="mb-3">
              <label for="senha" class="form-label">Nova Senha</label>
              <input type="password" name="senha" id="senha" class="form-control" required minlength="6" autofocus>
              <div class="form-text">A senha deve conter pelo menos 6 caracteres.</div>
            </div>

            <!-- ✅ Sugestão futura: incluir um campo de confirmação -->
            <!-- <div class="mb-3">
              <label for="senha2" class="form-label">Confirmar Senha</label>
              <input type="password" name="senha2" id="senha2" class="form-control" required minlength="6">
            </div> -->

            <button type="submit" class="btn btn-primary w-100">Salvar Nova Senha</button>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>

</body>
</html>
