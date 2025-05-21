<?php
// Garantindo que a sessão seja iniciada.
// Idealmente, session_start() deve ser chamado em um arquivo central (como config.php)
// que é incluído no início de todas as páginas.
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Inclui o arquivo de configuração e o cabeçalho.
// O caminho foi ajustado para refletir a estrutura de diretórios esperada:
// auth.php, atualizar_perfil.php, etc., estão em 'controllers'
// header.php, footer.php, etc., estão em 'includes'
// config.php está em 'config'
require_once __DIR__ . '/../../config/config.php';
include_once __DIR__ . '/../../includes/header.php'; // Inclui o header.php

// 🔐 Se o usuário já estiver logado, redireciona para o painel apropriado.
if (isset($_SESSION['usuario_id'])) {
    if ($_SESSION['usuario_tipo'] === 'admin') {
        header("Location: " . URL_BASE . "admin/");
    } else {
        header("Location: " . URL_BASE . "usuario/");
    }
    exit; // Importante para parar a execução após o redirecionamento.
}
?>

<div class="row justify-content-center">
  <div class="col-md-5">
    <h3 class="text-center mb-4">🔐 Login</h3>

    <?php
    // Exibe mensagens de erro (se houver) e depois as limpa da sessão.
    if (isset($_SESSION['erro'])): ?>
      <div class="alert alert-danger"><?= htmlspecialchars($_SESSION['erro']); unset($_SESSION['erro']); ?></div>
    <?php endif; ?>

    <?php
    // Exibe mensagens de sucesso (se houver) e depois as limpa da sessão.
    if (isset($_SESSION['sucesso'])): ?>
      <div class="alert alert-success"><?= htmlspecialchars($_SESSION['sucesso']); unset($_SESSION['sucesso']); ?></div>
    <?php endif; ?>

    <?php
    // Exibe mensagens de aviso (se houver) e depois as limpa da sessão.
    if (isset($_SESSION['aviso'])): ?>
      <div class="alert alert-warning"><?= htmlspecialchars($_SESSION['aviso']); unset($_SESSION['aviso']); ?></div>
    <?php endif; ?>

    <form action="<?= URL_BASE ?>controllers/auth.php" method="POST">
      <input type="hidden" name="acao" value="login">

      <div class="mb-3">
        <label for="usuario" class="form-label">Email</label>
        <input type="email" name="usuario" id="usuario" class="form-control" required autofocus>
      </div>

      <div class="mb-3">
        <label for="senha" class="form-label">Senha</label>
        <div class="input-group">
          <input type="password" name="senha" id="senha" class="form-control" required>
          <button type="button" class="btn btn-outline-secondary" onclick="toggleSenha(this)" aria-label="Mostrar/Ocultar Senha">
            <i class="bi bi-eye" id="toggleSenhaIcon"></i>
          </button>
        </div>
      </div>

      <button type="submit" class="btn btn-primary w-100">Entrar</button>
      <a href="register.php" class="d-block text-center mt-3">Não tenho conta? Cadastre-se aqui.</a>
    </form>
  </div>
</div>

<script>
/**
 * Alterna a visibilidade da senha no campo de input.
 * @param {HTMLElement} btn O botão que acionou a função.
 */
function toggleSenha(btn) {
  const input = btn.parentElement.querySelector('input'); // Encontra o input de senha
  const icon = btn.querySelector('i'); // Encontra o ícone dentro do botão

  if (input.type === 'password') {
    input.type = 'text';
    icon.classList.remove('bi-eye');
    icon.classList.add('bi-eye-slash');
    btn.setAttribute('title', 'Ocultar Senha');
  } else {
    input.type = 'password';
    icon.classList.remove('bi-eye-slash');
    icon.classList.add('bi-eye');
    btn.setAttribute('title', 'Mostrar Senha');
  }
}

// Define o foco inicial no campo de e-mail ao carregar a página
document.addEventListener('DOMContentLoaded', function() {
    const emailInput = document.getElementById('usuario');
    if (emailInput) {
        emailInput.focus();
    }
});
</script>

<?php include_once __DIR__ . '/../../includes/footer.php'; ?>