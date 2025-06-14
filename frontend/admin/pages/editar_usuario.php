<?php
session_start();
define('BASE_PATH', dirname(__DIR__, 3) . '/backend');

require_once BASE_PATH . '/config/config.php';
require_once BASE_PATH . '/includes/session.php';
require_once BASE_PATH . '/includes/verifica_admin.php';
require_once BASE_PATH . '/includes/protect_admin.php';
require_once BASE_PATH . '/includes/header.php';
require_once BASE_PATH . '/includes/menu.php';

exigir_login('admin');

// ⚠️ Validação do ID
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$id) {
    $_SESSION['erro'] = "ID inválido.";
    header("Location: usuarios.php");
    exit;
}

// 🧾 Processamento do formulário
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome       = trim($_POST['nome'] ?? '');
    $email      = trim($_POST['email'] ?? '');
    $tipo       = $_POST['tipo'] ?? 'usuario';
    $nova_senha = $_POST['senha'] ?? '';

    if ($nome === '' || $email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL) || !in_array($tipo, ['admin', 'usuario'])) {
        $_SESSION['erro'] = "Preencha os dados corretamente.";
        header("Location: editar_usuario.php?id=$id");
        exit;
    }

    try {
        if ($nova_senha !== '') {
            $senha_hash = password_hash($nova_senha, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("UPDATE usuarios SET nome = :nome, email = :email, tipo = :tipo, senha = :senha WHERE id = :id");
            $stmt->execute([
                ':nome'   => $nome,
                ':email'  => $email,
                ':tipo'   => $tipo,
                ':senha'  => $senha_hash,
                ':id'     => $id
            ]);
        } else {
            $stmt = $conn->prepare("UPDATE usuarios SET nome = :nome, email = :email, tipo = :tipo WHERE id = :id");
            $stmt->execute([
                ':nome'   => $nome,
                ':email'  => $email,
                ':tipo'   => $tipo,
                ':id'     => $id
            ]);
        }

        $_SESSION['sucesso'] = "✅ Usuário atualizado com sucesso.";
    } catch (PDOException $e) {
        $_SESSION['erro'] = "❌ Erro ao atualizar usuário: " . $e->getMessage();
    }

    header("Location: usuarios.php");
    exit;
}

// 🔍 Buscar dados do usuário para preencher o formulário
$stmt = $conn->prepare("SELECT nome, email, tipo FROM usuarios WHERE id = :id");
$stmt->execute([':id' => $id]);
$usuario = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$usuario) {
    $_SESSION['erro'] = "Usuário não encontrado.";
    header("Location: usuarios.php");
    exit;
}
?>

<div class="container py-4">
    <h3 class="mb-4">✏️ Editar Usuário</h3>

    <?php if (!empty($_SESSION['erro'])): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($_SESSION['erro']); unset($_SESSION['erro']); ?></div>
    <?php elseif (!empty($_SESSION['sucesso'])): ?>
        <div class="alert alert-success"><?= htmlspecialchars($_SESSION['sucesso']); unset($_SESSION['sucesso']); ?></div>
    <?php endif; ?>

    <form method="POST">
        <div class="mb-3">
            <label for="nome" class="form-label">Nome:</label>
            <input type="text" name="nome" id="nome" class="form-control" value="<?= htmlspecialchars($usuario['nome']) ?>" required>
        </div>

        <div class="mb-3">
            <label for="email" class="form-label">E-mail:</label>
            <input type="email" name="email" id="email" class="form-control" value="<?= htmlspecialchars($usuario['email']) ?>" required>
        </div>

        <div class="mb-3">
            <label for="tipo" class="form-label">Tipo de usuário:</label>
            <select name="tipo" id="tipo" class="form-select" required>
                <option value="usuario" <?= $usuario['tipo'] === 'usuario' ? 'selected' : '' ?>>Usuário</option>
                <option value="admin" <?= $usuario['tipo'] === 'admin' ? 'selected' : '' ?>>Administrador</option>
            </select>
        </div>

        <div class="mb-3">
            <label for="senha" class="form-label">Nova senha (opcional):</label>
            <input type="password" name="senha" id="senha" class="form-control">
            <div class="form-text">Deixe em branco para manter a senha atual.</div>
        </div>

        <button type="submit" class="btn btn-primary">💾 Salvar Alterações</button>
        <a href="usuarios.php" class="btn btn-secondary">↩️ Cancelar</a>
    </form>
</div>

<?php require_once BASE_PATH . '/includes/footer.php'; ?>
