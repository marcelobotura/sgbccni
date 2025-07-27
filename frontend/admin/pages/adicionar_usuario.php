<?php
require_once __DIR__ . '/../../../backend/config/config.php';
require_once __DIR__ . '/../../../backend/includes/session.php';
require_once __DIR__ . '/../../../backend/includes/protect_admin.php';
require_once __DIR__ . '/../../../backend/includes/header.php';
require_once __DIR__ . '/../../../backend/includes/menu.php';

exigir_login('admin');

// 📥 Processamento do formulário
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome  = trim($_POST['nome'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $senha = $_POST['senha'] ?? '';
    $tipo  = $_POST['tipo'] ?? 'usuario';

    if ($nome === '' || $email === '' || $senha === '') {
        $_SESSION['erro'] = "Preencha todos os campos obrigatórios.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['erro'] = "E-mail inválido.";
    } elseif (!in_array($tipo, ['usuario', 'admin', 'master'])) {
        $_SESSION['erro'] = "Tipo de usuário inválido.";
    } elseif ($tipo === 'master' && $_SESSION['usuario_tipo'] !== 'master') {
        $_SESSION['erro'] = "Apenas um usuário MASTER pode criar outro MASTER.";
    } else {
        // Verifica duplicidade de e-mail
        $stmt = $pdo->prepare("SELECT id FROM usuarios WHERE email = ?");
        $stmt->execute([$email]);

        if ($stmt->rowCount() > 0) {
            $_SESSION['erro'] = "E-mail já cadastrado.";
        } else {
            try {
                $senhaHash = password_hash($senha, PASSWORD_DEFAULT);
                $stmt = $pdo->prepare("INSERT INTO usuarios (nome, email, senha, tipo, criado_em) VALUES (?, ?, ?, ?, NOW())");
                $stmt->execute([$nome, $email, $senhaHash, $tipo]);

                $_SESSION['sucesso'] = "✅ Usuário cadastrado com sucesso.";
                header("Location: gerenciar_usuarios.php");
                exit;
            } catch (PDOException $e) {
                $_SESSION['erro'] = "❌ Erro ao cadastrar: " . $e->getMessage();
            }
        }
    }
}
?>

<div class="container py-4">
    <h2 class="mb-4">👤 Adicionar Novo Usuário</h2>

    <?php if (!empty($_SESSION['erro'])): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($_SESSION['erro']); unset($_SESSION['erro']); ?></div>
    <?php endif; ?>

    <form method="post" class="card shadow-sm p-4">
        <div class="mb-3">
            <label for="nome" class="form-label">Nome completo</label>
            <input type="text" name="nome" id="nome" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="email" class="form-label">E-mail</label>
            <input type="email" name="email" id="email" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="senha" class="form-label">Senha</label>
            <input type="password" name="senha" id="senha" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="tipo" class="form-label">Tipo de usuário</label>
            <select name="tipo" id="tipo" class="form-select" required>
                <option value="usuario" selected>Usuário comum</option>
                <option value="admin">Administrador</option>
                <?php if ($_SESSION['usuario_tipo'] === 'master'): ?>
                    <option value="master">Master</option>
                <?php endif; ?>
            </select>
        </div>

        <div class="d-flex">
            <button type="submit" class="btn btn-success">
                <i class="bi bi-person-plus me-1"></i> Cadastrar
            </button>
            <a href="gerenciar_usuarios.php" class="btn btn-outline-secondary ms-2">
                <i class="bi bi-arrow-left"></i> Voltar
            </a>
        </div>
    </form>
</div>

<?php require_once __DIR__ . '/../../../backend/includes/footer.php'; ?>
