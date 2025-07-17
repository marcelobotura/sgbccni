<?php
require_once __DIR__ . '/../../../backend/config/config.php';
require_once __DIR__ . '/../../../backend/includes/session.php';
require_once __DIR__ . '/../../../backend/includes/protect_admin.php';
require_once __DIR__ . '/../../../backend/includes/header.php';
require_once __DIR__ . '/../../../backend/includes/menu.php';

exigir_login('admin');

// Lista de tipos válidos (deve coincidir com os tipos do ENUM no banco de dados)
$tipos_validos = ['autor', 'categoria', 'editora', 'outro', 'volume', 'edicao', 'tipo', 'formato'];

// Processa o formulário
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = trim($_POST['nome'] ?? '');
    $tipo = $_POST['tipo'] ?? '';

    if (empty($nome) || empty($tipo)) {
        $_SESSION['erro'] = "Preencha todos os campos.";
    } elseif (!in_array($tipo, $tipos_validos)) {
        $_SESSION['erro'] = "Tipo de tag inválido.";
    } else {
        try {
            $stmt = $pdo->prepare("INSERT INTO tags (nome, tipo) VALUES (?, ?)");
            $stmt->execute([$nome, $tipo]);

            $_SESSION['sucesso'] = "Tag adicionada com sucesso.";
            header("Location: gerenciar_tags.php");
            exit;
        } catch (PDOException $e) {
            $_SESSION['erro'] = "Erro ao salvar tag: " . $e->getMessage();
        }
    }
}
?>

<div class="container py-4">
    <h2 class="mb-4">➕ Nova Tag</h2>

    <?php if (!empty($_SESSION['sucesso'])): ?>
        <div class="alert alert-success"><?= htmlspecialchars($_SESSION['sucesso']); unset($_SESSION['sucesso']); ?></div>
    <?php elseif (!empty($_SESSION['erro'])): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($_SESSION['erro']); unset($_SESSION['erro']); ?></div>
    <?php endif; ?>

    <form method="post" class="card shadow-sm p-4">
        <div class="mb-3">
            <label for="nome" class="form-label">Nome da Tag</label>
            <input type="text" name="nome" id="nome" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="tipo" class="form-label">Tipo</label>
            <select name="tipo" id="tipo" class="form-select" required>
                <option value="">Selecione o tipo</option>
                <?php foreach ($tipos_validos as $tipo): ?>
                    <option value="<?= htmlspecialchars($tipo) ?>"><?= ucfirst($tipo) ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <button type="submit" class="btn btn-success">
            <i class="bi bi-check-lg me-1"></i> Salvar Tag
        </button>
        <a href="gerenciar_tags.php" class="btn btn-outline-secondary ms-2">
            <i class="bi bi-arrow-left"></i> Cancelar
        </a>
    </form>
</div>

<?php require_once __DIR__ . '/../../../backend/includes/footer.php'; ?>
