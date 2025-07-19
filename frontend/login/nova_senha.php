<?php
require_once __DIR__ . '/../../config/env.php';
require_once __DIR__ . '/../../backend/config/config.php';
require_once __DIR__ . '/../../backend/includes/session.php';

$token = $_GET['token'] ?? '';
$tipo_usuario = 'usuario'; // valor padrão

if (!$token) {
    $_SESSION['erro'] = "Token de redefinição ausente.";
    header("Location: " . URL_BASE . "login/redefinir_senha.php");
    exit;
}

// Verifica se token é válido e busca tipo de usuário
$stmt_token = $conn->prepare("
    SELECT u.tipo 
    FROM tokens_recuperacao tr
    INNER JOIN usuarios u ON tr.usuario_id = u.id
    WHERE tr.token = ? AND tr.expira_em > NOW()
");
$stmt_token->bind_param("s", $token);
$stmt_token->execute();
$stmt_token->store_result();

if ($stmt_token->num_rows === 0) {
    $_SESSION['erro'] = "Token de redefinição inválido ou expirado.";
    $stmt_token->close();
    header("Location: " . URL_BASE . "login/redefinir_senha.php");
    exit;
}

$stmt_token->bind_result($tipo_usuario);
$stmt_token->fetch();
$stmt_token->close();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <title>Nova Senha - <?= NOME_SISTEMA ?></title>
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- Estilos -->
  <link rel="stylesheet" href="<?= URL_BASE ?>frontend/assets/css/base.css">
  <link rel="stylesheet" href="<?= URL_BASE ?>frontend/assets/css/layout.css">
  <link rel="stylesheet" href="<?= URL_BASE ?>frontend/assets/css/components.css">
  <link rel="stylesheet" href="<?= URL_BASE ?>frontend/assets/css/utilities.css">
  <link rel="stylesheet" href="<?= URL_BASE ?>frontend/assets/css/pages/login.css">
  <link rel="stylesheet" href="<?= URL_BASE ?>frontend/assets/css/themes/light.css" id="theme-style">
</head>
<body>

  <div class="login-box">
    <h2>🔒 Redefinir Senha</h2>

    <?php if (isset($_SESSION['erro'])): ?>
      <div class="alerta-erro"><?= $_SESSION['erro']; unset($_SESSION['erro']); ?></div>
    <?php endif; ?>

    <?php if (isset($_SESSION['sucesso'])): ?>
      <div class="alerta-sucesso"><?= $_SESSION['sucesso']; unset($_SESSION['sucesso']); ?></div>
    <?php endif; ?>

    <form action="<?= URL_BASE ?>backend/controllers/auth/salvar_nova_senha.php?token=<?= urlencode($token) ?>" method="POST" autocomplete="off">
      <input type="hidden" name="tipo" value="<?= htmlspecialchars($tipo_usuario) ?>">

      <div class="form-group">
        <input type="password" name="senha" id="senha" class="form-control" placeholder="Nova Senha" required minlength="6">
        <button type="button" class="toggle-password" onclick="toggleSenha(this, 'senha')">👁️</button>
      </div>

      <div class="form-group">
        <input type="password" name="senha2" id="senha2" class="form-control" placeholder="Confirmar Senha" required minlength="6">
        <button type="button" class="toggle-password" onclick="toggleSenha(this, 'senha2')">👁️</button>
      </div>

      <button type="submit" class="btn">Redefinir Senha</button>
    </form>

    <div class="link-cadastro mt-3">
      <?php if ($tipo_usuario === 'admin'): ?>
        <a href="<?= URL_BASE ?>login/login_admin.php">← Voltar ao login de administrador</a>
      <?php else: ?>
        <a href="<?= URL_BASE ?>login/login_user.php">← Voltar ao login de usuário</a>
      <?php endif; ?>
    </div>
  </div>

  <script>
    function toggleSenha(el, id) {
      const input = document.getElementById(id);
      input.type = input.type === 'password' ? 'text' : 'password';
      el.textContent = input.type === 'password' ? '👁️' : '🙈';
    }
  </script>

</body>
</html>
