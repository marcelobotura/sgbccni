<?php
define('BASE_PATH', dirname(__DIR__) . '/../app_backend');
require_once BASE_PATH . '/config/config.php';

$token = $_GET['token'] ?? '';

if (!$token) {
    die('<h3>❌ Token inválido.</h3>');
}

// Verifica se o token é válido e não expirou
$stmt = $conn->prepare("SELECT u.id, u.nome FROM tokens_recuperacao t 
    JOIN usuarios u ON u.id = t.usuario_id 
    WHERE t.token = ? AND t.expira_em > NOW()");
$stmt->bind_param("s", $token);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows !== 1) {
    die('<h3>❌ Token inválido ou expirado.</h3>');
}

$usuario = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <title>Redefinir Senha - Biblioteca CNI</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
  <div class="container py-5">
    <div class="row justify-content-center">
      <div class="col-md-6">
        <div class="card shadow">
          <div class="card-header bg-primary text-white">
            <h4 class="mb-0">Redefinir Senha</h4>
          </div>
          <div class="card-body">
            <form action="salvar_nova_senha.php" method="post">
              <input type="hidden" name="token" value="<?= htmlspecialchars($token) ?>">
              <div class="mb-3">
                <label>Nova Senha:</label>
                <input type="password" name="senha" class="form-control" required>
              </div>
              <div class="mb-3">
                <label>Confirmar Senha:</label>
                <input type="password" name="confirmar" class="form-control" required>
              </div>
              <button type="submit" class="btn btn-success w-100">Salvar Nova Senha</button>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</body>
</html>
