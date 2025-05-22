<?php
session_start();
require_once __DIR__ . '/../config/config.php';

$erro = '';

// Redireciona se já estiver logado
if (isset($_SESSION['usuario_id']) && $_SESSION['usuario_tipo'] === 'admin') {
    header("Location: ../admin/pages/index.php");
    exit;
}

// Processa login
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = trim($_POST['email']);
    $senha = $_POST['senha'];

    $stmt = $conn->prepare("SELECT id, nome, senha, tipo FROM usuarios WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows === 1) {
        $stmt->bind_result($id, $nome, $senha_hash, $tipo);
        $stmt->fetch();

        if ($tipo === 'admin') {
            if (password_verify($senha, $senha_hash)) {
                $_SESSION['usuario_id']   = $id;
                $_SESSION['usuario_nome'] = $nome;
                $_SESSION['usuario_tipo'] = 'admin';
                $_SESSION['admin_logado'] = true;

                header("Location: ../admin/pages/index.php");
                exit;
            } else {
                $erro = "Senha incorreta.";
            }
        } else {
            $erro = "Este usuário não é um administrador.";
        }
    } else {
        $erro = "Administrador não encontrado.";
    }

    $stmt->close();
}

require_once __DIR__ . '/../includes/header.php';
?>

<div class="container d-flex justify-content-center align-items-center" style="min-height: 80vh;">
  <div class="login-box p-4 border rounded shadow-sm" style="max-width: 400px; width: 100%; background-color: var(--card-color, #2c2c2c); color: var(--text-color, #fff);">
    <h3 class="mb-4 text-center">🔐 Login Administrativo</h3>
    
    <form method="POST">
      <div class="mb-3">
        <input type="email" name="email" class="form-control" placeholder="E-mail do administrador" required>
      </div>
      <div class="mb-3">
        <input type="password" name="senha" class="form-control" placeholder="Senha" required>
      </div>
      <?php if (!empty($erro)): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($erro) ?></div>
      <?php endif; ?>
      <button type="submit" class="btn btn-primary w-100">Entrar</button>
      <a href="register.php" class="btn btn-outline-light w-100 mt-2">Cadastrar novo administrador</a>
    </form>
  </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
