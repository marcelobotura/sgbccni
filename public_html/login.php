<?php
require_once __DIR__ . '/../backend/config/config.php';
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <title>Login - <?= NOME_SISTEMA ?></title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container d-flex justify-content-center align-items-center min-vh-100">
  <div class="card shadow-sm p-4" style="width: 100%; max-width: 400px;">
    <h3 class="text-center mb-4">🔐 Login</h3>

    <div id="mensagem" class="alert d-none"></div>

    <form id="formLogin">
      <div class="mb-3">
        <label for="email" class="form-label">E-mail:</label>
        <input type="email" name="email" id="email" class="form-control" required>
      </div>

      <div class="mb-3">
        <label for="senha" class="form-label">Senha:</label>
        <input type="password" name="senha" id="senha" class="form-control" required>
      </div>

      <button type="submit" class="btn btn-primary w-100">Entrar</button>
    </form>

    <div class="text-center mt-3">
      <a href="<?= URL_BASE ?>frontend/login/register.php">Criar uma conta</a>
    </div>
  </div>
</div>

<script>
document.querySelector('#formLogin').addEventListener('submit', function(e) {
  e.preventDefault();

  const form = new FormData(this);
  const mensagem = document.getElementById('mensagem');

  fetch('../backend/controllers/auth/login_valida.php', {
    method: 'POST',
    body: form
  })
  .then(res => res.json())
  .then(data => {
    mensagem.classList.remove('d-none', 'alert-success', 'alert-danger');

    if (data.status === 'sucesso') {
      mensagem.classList.add('alert-success');
      mensagem.textContent = data.mensagem;
      setTimeout(() => {
        window.location.href = data.tipo === 'admin' ? 'frontend/admin/pages/index.php' : 'frontend/usuario/meus_livros.php';
      }, 1000);
    } else {
      mensagem.classList.add('alert-danger');
      mensagem.textContent = data.mensagem;
    }
  })
  .catch(() => {
    mensagem.classList.remove('d-none');
    mensagem.classList.add('alert-danger');
    mensagem.textContent = 'Erro ao processar login.';
  });
});
</script>

</body>
</html>
