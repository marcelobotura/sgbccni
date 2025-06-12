<?php
session_start();

if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_tipo'] !== 'usuario') {
    header('Location: ../login/login_user.php');
    exit;
}

require_once __DIR__ . '/../backend/config/config.php';

$usuario_id = $_SESSION['usuario_id'];
$mensagem = '';
$erro = '';

// Processa o formulário
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = trim($_POST['nome']);
    $senha_atual = $_POST['senha_atual'] ?? '';
    $nova_senha = $_POST['nova_senha'] ?? '';
    $confirmar_senha = $_POST['confirmar_senha'] ?? '';

    // Atualiza nome
    $stmt = $conn->prepare("UPDATE usuarios SET nome = ? WHERE id = ?");
    $stmt->bind_param("si", $nome, $usuario_id);
    $stmt->execute();
    $_SESSION['usuario_nome'] = $nome;
    $mensagem = "Nome atualizado com sucesso.";

    // Atualiza senha se preenchida
    if (!empty($senha_atual) && !empty($nova_senha) && !empty($confirmar_senha)) {
        $stmt = $conn->prepare("SELECT senha FROM usuarios WHERE id = ?");
        $stmt->bind_param("i", $usuario_id);
        $stmt->execute();
        $stmt->bind_result($senha_hash);
        $stmt->fetch();
        $stmt->close();

        if (password_verify($senha_atual, $senha_hash)) {
            if ($nova_senha === $confirmar_senha) {
                if (strlen($nova_senha) >= 6) {
                    $nova_hash = password_hash($nova_senha, PASSWORD_DEFAULT);
                    $stmt = $conn->prepare("UPDATE usuarios SET senha = ? WHERE id = ?");
                    $stmt->bind_param("si", $nova_hash, $usuario_id);
                    $stmt->execute();
                    $mensagem .= " Senha atualizada com sucesso.";
                } else {
                    $erro = "A nova senha deve ter pelo menos 6 caracteres.";
                }
            } else {
                $erro = "As novas senhas não coincidem.";
            }
        } else {
            $erro = "Senha atual incorreta.";
        }
    }
}

// Busca nome atual
$stmt = $conn->prepare("SELECT nome FROM usuarios WHERE id = ?");
$stmt->bind_param("i", $usuario_id);
$stmt->execute();
$stmt->bind_result($nome_atual);
$stmt->fetch();
$stmt->close();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <title>Editar Conta - Biblioteca CNI</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="../assets/css/base.css">
  <link rel="stylesheet" href="../assets/css/layout.css">
  <link rel="stylesheet" href="../assets/css/components.css">
  <link rel="stylesheet" href="../assets/css/themes/light.css" id="theme-style">
</head>
<body>
  <div class="container py-5">
    <h2 class="mb-4">⚙️ Editar Conta</h2>

    <?php if ($mensagem): ?>
      <div class="alert alert-success"><?= htmlspecialchars($mensagem) ?></div>
    <?php endif; ?>

    <?php if ($erro): ?>
      <div class="alert alert-danger"><?= htmlspecialchars($erro) ?></div>
    <?php endif; ?>

    <form method="POST" class="card p-4 shadow-sm">
      <div class="mb-3">
        <label for="nome" class="form-label">Nome</label>
        <input type="text" name="nome" id="nome" class="form-control" value="<?= htmlspecialchars($nome_atual) ?>" required>
      </div>

      <hr>
      <p class="text-muted mb-2">🔐 Alterar Senha (opcional):</p>

      <div class="mb-3">
        <label for="senha_atual" class="form-label">Senha Atual</label>
        <input type="password" name="senha_atual" id="senha_atual" class="form-control">
      </div>
      <div class="mb-3">
        <label for="nova_senha" class="form-label">Nova Senha</label>
        <input type="password" name="nova_senha" id="nova_senha" class="form-control">
      </div>
      <div class="mb-3">
        <label for="confirmar_senha" class="form-label">Confirmar Nova Senha</label>
        <input type="password" name="confirmar_senha" id="confirmar_senha" class="form-control">
      </div>

      <div class="d-flex justify-content-between">
        <a href="painel_usuario.php" class="btn btn-outline-secondary">← Voltar</a>
        <button type="submit" class="btn btn-primary">Salvar Alterações</button>
      </div>
    </form>
  </div>
</body>
</html>
