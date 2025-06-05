<?php
require_once __DIR__ . '/../backend/config/config.php';
require_once __DIR__ . '/../backend/includes/session.php';
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Login - <?= NOME_SISTEMA ?></title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
  <link href="<?= URL_BASE ?>frontend/assets/css/estilo-base.css" rel="stylesheet">
  <link href="<?= URL_BASE ?>frontend/assets/css/estilo-<?= $_COOKIE['modo_tema'] ?? 'claro' ?>.css" rel="stylesheet">
  <script src="<?= URL_BASE ?>frontend/assets/js/tema.js"></script>
  <style>
    .toggle-password {
      position: absolute;
      top: 50%;
      right: 1rem;
      transform: translateY(-50%);
      cursor: pointer;
    }
  </style>
</head>
<body class="bg-body-secondary">

<div class="container d-flex justify-content-center align-items-center min-vh-100">
  <div class="card shadow p-4" style="max-width: 420px; width: 100%; position: relative;">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <h4 class="fw-bold m-0">🔐 Login</h4>
      <button onclick="alternarTema()" class="btn btn-sm btn-outline-secondary" title="Tema">
        <i class="bi bi-brightness-high"></i>
      </button>
    </div>

    <div id="mensagem" class="alert d-none"></div>

    <form id="formLogin">
      <div class="mb-3">
        <label for="email" class="form-label">E-mail</label>
        <input type="email" name="email" id="email" class="form-control" required>
      </div>

      <div class="mb-3 position-relative">
        <label for="senha" class="form-label">Senha</label>
        <input type="password" name="senha" id="senha" class="form-control" required>
        <i class="bi bi-eye toggle-password" onclick="togglePassword()"></i>
      </div>

      <button type="submit" class="btn btn-primary w-100">Entrar</button>
    </form>

    <div class="text-center mt-3">
      <a href="<?= URL_BASE ?>frontend/login/register_usuario.php" class="btn btn-outline-success px-4 ms-2">Criar Conta</a>
    </div>
  </div>
</div>

<script>
function togglePassword() {
  const senha = document.getElementById('senha');
  const icone = document.querySelector('.toggle-password');
  if (senha.type === 'password') {
    senha.type = 'text';
    icone.classList.replace('bi-eye', 'bi-eye-slash');
  } else {
    senha.type = 'password';
    icone.classList.replace('bi-eye-slash', 'bi-eye');
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
          ? "<?= URL_BASE ?>frontend/admin/pages/index.php"
          : "<?= URL_BASE ?>frontend/usuario/index.php";
      }, 800);
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
<?php require_once __DIR__ . '/../backend/includes/footer.php'; ?>
</body>
</html>
