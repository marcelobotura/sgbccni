<?php
define('BASE_PATH', dirname(__DIR__, 2) . '/backend');
require_once BASE_PATH . '/config/config.php';
require_once BASE_PATH . '/includes/session.php';
require_once __DIR__ . '/protect_usuario.php';

exigir_login('usuario');

$id = $_SESSION['usuario_id'];
$foto = $_SESSION['usuario_foto'] ?? null;
$erro = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $senha = $_POST['senha'] ?? '';

  if (empty($senha)) {
    $erro = "Digite sua senha para confirmar.";
  } else {
    $stmt = $pdo->prepare("SELECT senha FROM usuarios WHERE id = ?");
    $stmt->execute([$id]);
    $senha_hash = $stmt->fetchColumn();

    if ($senha_hash && password_verify($senha, $senha_hash)) {
      if ($foto && file_exists(dirname(__DIR__, 2) . "/uploads/perfis/" . $foto)) {
        unlink(dirname(__DIR__, 2) . "/uploads/perfis/" . $foto);
      }

      $pdo->prepare("DELETE FROM livros_usuarios WHERE usuario_id = ?")->execute([$id]);
      $pdo->prepare("DELETE FROM usuarios WHERE id = ?")->execute([$id]);

      session_destroy();
      header("Location: ../login/login.php?msg=conta_excluida");
      exit;
    } else {
      $erro = "Senha incorreta. Tente novamente.";
    }
  }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <title>Excluir Conta</title>
  <link rel="stylesheet" href="<?= URL_BASE ?>frontend/assets/css/base.css">
</head>
<body class="container py-5">
  <h2 class="text-danger mb-4"><i class="bi bi-trash"></i> Excluir Conta</h2>

  <?php if ($erro): ?>
    <div class="alert alert-danger"><?= htmlspecialchars($erro) ?></div>
  <?php endif; ?>

  <form method="POST" class="card p-4 shadow-sm">
    <p class="mb-3">Tem certeza que deseja excluir sua conta? Esta ação não poderá ser desfeita.</p>
    <div class="mb-3">
      <label for="senha">Confirme sua senha:</label>
      <input type="password" name=
