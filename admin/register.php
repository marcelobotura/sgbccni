<?php
session_start();
require_once __DIR__ . '/../config/config.php';

$erro = '';
$sucesso = '';

// Verifica envio do formulário
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nome  = trim($_POST['nome']);
    $email = trim($_POST['email']);
    $senha = $_POST['senha'];
    $tipo  = 'usuario'; // padrão

    if (empty($nome) || empty($email) || empty($senha)) {
        $erro = "Preencha todos os campos.";
    } else {
        // Verifica se já existe o e-mail
        $stmt = $conn->prepare("SELECT id FROM usuarios WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $erro = "Este e-mail já está cadastrado.";
        } else {
            $stmt->close();

            $senha_hash = password_hash($senha, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("INSERT INTO usuarios (nome, email, senha, tipo) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("ssss", $nome, $email, $senha_hash, $tipo);

            if ($stmt->execute()) {
                $_SESSION['usuario_id']   = $stmt->insert_id;
                $_SESSION['usuario_nome'] = $nome;
                $_SESSION['usuario_tipo'] = $tipo;

                // Redireciona para painel do usuário
                header("Location: login.php");
                exit;
            } else {
                $erro = "Erro ao cadastrar. Tente novamente.";
            }
        }

        $stmt->close();
    }
}

require_once __DIR__ . '/../includes/header.php';
?>

<div class="container d-flex justify-content-center align-items-center" style="min-height: 80vh;">
  <div class="p-4 border rounded shadow-sm" style="max-width: 500px; width: 100%; background-color: var(--card-color, #2c2c2c); color: var(--text-color, #fff);">
    <h3 class="mb-4 text-center">👤 Criar Conta</h3>

    <?php if ($erro): ?>
      <div class="alert alert-danger"><?= htmlspecialchars($erro) ?></div>
    <?php elseif ($sucesso): ?>
      <div class="alert alert-success"><?= htmlspecialchars($sucesso) ?></div>
    <?php endif; ?>

    <form method="POST">
      <div class="mb-3">
        <label>Nome completo</label>
        <input type="text" name="nome" class="form-control" required>
      </div>
      <div class="mb-3">
        <label>E-mail</label>
        <input type="email" name="email" class="form-control" required>
      </div>
      <div class="mb-3">
        <label>Senha</label>
        <input type="password" name="senha" class="form-control" required>
      </div>
      <button type="submit" class="btn btn-success w-100">Cadastrar</button>
      <a href="login.php" class="btn btn-outline-light w-100 mt-2">Já tenho uma conta</a>
    </form>
  </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
