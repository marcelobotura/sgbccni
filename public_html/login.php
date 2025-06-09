<?php
require_once __DIR__ . '/../backend/config/config.php';
require_once __DIR__ . '/../backend/includes/session.php';
?>
<!DOCTYPE html>
<html lang="pt-br" data-tema="<?= htmlspecialchars($_COOKIE['modo_tema'] ?? 'claro') ?>">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Login - <?= NOME_SISTEMA ?></title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

  <link href="<?= URL_BASE ?>frontend/assets/css/estilo-base.css" rel="stylesheet">
  <link href="<?= URL_BASE ?>frontend/assets/css/estilo-<?= $_COOKIE['modo_tema'] ?? 'claro' ?>.css" rel="stylesheet">

  <link href="<?= URL_BASE ?>frontend/assets/css/auth-forms.css" rel="stylesheet">

  <script src="<?= URL_BASE ?>frontend/assets/js/tema.js"></script>
</head>
<body class="bg-body-secondary">

<div class="container py-5">
  <div class="row justify-content-center">
    <div class="col-md-5">
      <div class="card shadow border-0">
        <div class="card-body">
          <h4 class="mb-4 text-center">👋 Bem-vindo(a) de volta!</h4>

          <div id="mensagem" class="alert d-none" role="alert"></div>

          <form id="formLogin" method="POST" autocomplete="off">
            <div class="mb-3">
              <label for="email" class="form-label">E-mail:</label>
              <input type="email" name="email" id="email" class="form-control" required autofocus placeholder="seu.email@exemplo.com">
            </div>

            <div class="mb-3 position-relative">
              <label for="senha" class="form-label">Senha:</label>
              <input type="password" name="senha" id="senha" class="form-control" required placeholder="••••••••">
              <i class="bi bi-eye toggle-password" onclick="toggleSenha(this)"></i>
            </div>

            <button type="submit" class="btn btn-primary w-100">Entrar</button>
          </form>

          <div class="mt-3 text-center">
            <a href="<?= URL_BASE ?>frontend/login/register_usuario.php">✍️ Criar uma conta</a>
          </div>
          <div class="mt-2 text-center">
            <a href="<?= URL_BASE ?>frontend/login/redefinir_senha.php">❓ Esqueceu a senha?</a>
          </div>

        </div>
      </div>
    </div>
  </div>
</div>

<script>
function toggleSenha(icon) {
  const senha = document.getElementById('senha');
  if (senha.type === 'password') {
    senha.type = 'text';
    icon.classList.replace('bi-eye', 'bi-eye-slash');
  } else {
    senha.type = 'password';
    icon.classList.replace('bi-eye-slash', 'bi-eye');
  }
}

document.querySelector('#formLogin').addEventListener('submit', function(e) {
  e.preventDefault();
  const form = new FormData(this);
  const mensagem = document.getElementById('mensagem');

  fetch("<?= URL_BASE ?>backend/controllers/auth/login_valida.php", {
    method: 'POST',
    body: form
  })
  .then(r => r.json())
  .then(data => {
    mensagem.classList.remove('d-none', 'alert-success', 'alert-danger');
    if (data.status === 'sucesso') {
      mensagem.classList.add('alert-success');
      mensagem.textContent = data.mensagem;
      setTimeout(() => {
        window.location.href = data.tipo === 'admin'
          ? "<?= URL_BASE ?>frontend/admin/dashboard.php" // Ajuste para dashboard.php
          : "<?= URL_BASE ?>frontend/usuario/index.php"; // Ou outro diretório padrão de usuário
      }, 800);
    } else {
      mensagem.classList.add('alert-danger');
      mensagem.textContent = data.mensagem;
    }
    mensagem.classList.remove('d-none'); // Garante que a mensagem seja visível
  })
  .catch(error => {
    console.error('Erro na requisição:', error);
    mensagem.classList.remove('d-none', 'alert-success');
    mensagem.classList.add('alert-danger');
    mensagem.textContent = 'Ocorreu um erro inesperado. Tente novamente.';
  });
});
</script>

</body>
</html>