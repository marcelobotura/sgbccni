<?php
define('BASE_PATH', dirname(__DIR__) . '/../app_backend');
require_once BASE_PATH . '/config/config.php';
require_once BASE_PATH . '/includes/session.php';

if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_tipo'] !== 'admin') {
    header("Location: ../login/index.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <title>Painel Administrativo</title>
</head>
<body>
  <h2>🎛️ Painel do Admin: <?= htmlspecialchars($_SESSION['usuario_nome']) ?></h2>
  <p>Aqui você pode gerenciar livros, usuários e relatórios.</p>
  <a href="../login/logout.php">Sair</a>
</body>
</html>
