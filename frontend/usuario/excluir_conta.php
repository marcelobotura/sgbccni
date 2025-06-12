<?php
session_start();

if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_tipo'] !== 'usuario') {
    header('Location: ../login/login_user.php');
    exit;
}

require_once __DIR__ . '/../backend/config/config.php';

$usuario_id = $_SESSION['usuario_id'];
$erro = '';
$sucesso = '';

// Processa o formulário
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $senha = $_POST['senha'] ?? '';

    if (!empty($senha)) {
        // Verifica senha
        $stmt = $conn->prepare("SELECT senha FROM usuarios WHERE id = ?");
        $stmt->bind_param("i", $usuario_id);
        $stmt->execute();
        $stmt->bind_result($senha_hash);
        $stmt->fetch();
        $stmt->close();

        if (password_verify($senha, $senha_hash)) {
            // Apaga dados de livros_usuarios relacionados
            $stmt = $conn->prepare("DELETE FROM livros_usuarios WHERE usuario_id = ?");
            $stmt->bind_param("i", $usuario_id);
            $stmt->execute();

            // Apaga o usuário
            $stmt = $conn->prepare("DELETE FROM usuarios WHERE id = ?");
            $stmt->bind_param("i", $usuario_id);
            $stmt->execute();

            // Finaliza sessão
            session_destroy();
            $sucesso = "Sua conta foi excluída com sucesso.";
            header("Location: ../login/login_user.php?excluido=1");
            exit;
        } else {
            $erro = "Senha incorreta. Tente novamente.";
        }
    } else {
        $erro = "Informe sua senha para confirmar a exclusão.";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <title>Excluir Conta - Biblioteca CNI</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="../assets/css/base.css">
  <link rel="stylesheet" href="../assets/css/layout.css">
  <link rel="stylesheet" href="../assets/css/components.css">
  <link rel="stylesheet" href="../assets/css/themes/light.css" id="theme-style">
</head>
<body>
  <div class="container py-5">
    <h2 class="text-danger mb-4">⚠️ Excluir Conta</h2>

    <?php if ($erro): ?>
      <div class="alert alert-danger"><?= htmlspecialchars($erro) ?></div>
    <?php endif; ?>

    <div class="alert alert-warning">
      Esta ação é <strong>irreversível</strong>. Todos os seus dados, favoritos, históricos e empréstimos serão apagados.
    </div>

    <form method="POST" class="card p-4 shadow-sm">
      <div class="mb-3">
        <label for="senha" class="form-label">Digite sua senha para confirmar:</label>
        <input type="password" name="senha" id="senha" class="form-control" required>
      </div>

      <div class="d-flex justify-content-between">
        <a href="painel_usuario.php" class="btn btn-outline-secondary">← Cancelar</a>
        <button type="submit" class="btn btn-danger" onclick="return confirm('Tem certeza que deseja excluir sua conta?')">Excluir Minha Conta</button>
      </div>
    </form>
  </div>
</body>
</html>
