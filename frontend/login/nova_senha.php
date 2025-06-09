<?php
require_once __DIR__ . '/../../config/env.php'; // ✅ Garante que URL_BASE e constantes estão carregadas
require_once __DIR__ . '/../../backend/config/config.php'; // Inclui a conexão com o banco e sessões
require_once __DIR__ . '/../../backend/includes/session.php'; // Garante sessão ativa

$token = $_GET['token'] ?? '';
if (!$token) {
    $_SESSION['erro'] = "Token de redefinição ausente.";
    header("Location: " . URL_BASE . "login/redefinir_senha.php");
    exit;
}

// 🔎 Verifica se o token é válido e não expirou
$stmt_token = $conn->prepare("SELECT usuario_id FROM tokens_recuperacao WHERE token = ? AND expira_em > NOW()");
$stmt_token->bind_param("s", $token);
$stmt_token->execute();
$stmt_token->store_result();

if ($stmt_token->num_rows === 0) {
    $_SESSION['erro'] = "Token de redefinição inválido ou expirado. Solicite uma nova redefinição.";
    $stmt_token->close();
    header("Location: " . URL_BASE . "login/redefinir_senha.php");
    exit;
}
$stmt_token->close();

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

          <?php include_once BASE_PATH . '/includes/alerta.php'; ?>

          <form action="salvar_nova_senha.php?token=<?= urlencode($token) ?>" method="POST" autocomplete="off">
            <div class="mb-3">
              <label for="senha" class="form-label">Nova Senha</label>
              <input type="password" name="senha" id="senha" class="form-control" required minlength="6" autofocus>
              <div class="form-text">A senha deve conter pelo menos 6 caracteres.</div>
            </div>

            <div class="mb-3"> <label for="senha2" class="form-label">Confirmar Senha</label>
              <input type="password" name="senha2" id="senha2" class="form-control" required minlength="6">
              <div class="form-text">Repita sua nova senha.</div>
            </div>

            <button type="submit" class="btn btn-primary w-100 mt-3">Redefinir Senha</button>
          </form>

        </div>
      </div>
    </div>
  </div>
</div>

<?php include_once BASE_PATH . '/includes/footer.php'; ?>