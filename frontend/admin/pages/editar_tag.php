<?php
require_once __DIR__ . '/../../../backend/config/config.php';
require_once __DIR__ . '/../../../backend/includes/session.php';
require_once __DIR__ . '/../../../backend/includes/protect_admin.php';
require_once __DIR__ . '/../../../backend/includes/header.php';
require_once __DIR__ . '/../../../backend/includes/menu.php';

exigir_login('admin');

// Lista de tipos válidos (deve coincidir com ENUM no banco)
$tipos_validos = ['autor', 'categoria', 'editora', 'outro', 'volume', 'edicao', 'tipo', 'formato'];

// Verifica ID
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    $_SESSION['erro'] = "ID inválido.";
    header("Location: gerenciar_tags.php");
    exit;
}

$id = (int) $_GET['id'];

// Busca dados atuais da tag
try {
    $stmt = $pdo->prepare("SELECT * FROM tags WHERE id = ?");
    $stmt->execute([$id]);
    $tag = $stmt->fetch();

    if (!$tag) {
        $_SESSION['erro'] = "Tag não encontrada.";
        header("Location: gerenciar_tags.php");
        exit;
    }
} catch (PDOException $e) {
    $_SESSION['erro'] = "Erro ao buscar tag: " . $e->getMessage();
    header("Location: gerenciar_tags.php");
    exit;
}

// Processamento da edição
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = trim($_POST['nome'] ?? '');
    $tipo = $_POST['tipo'] ?? '';

    if (empty($nome) || empty($tipo)) {
        $_SESSION['erro'] = "Preencha todos os campos.";
    } elseif (!in_array($tipo, $tipos_validos)) {
        $_SESSION['erro'] = "Tipo de tag inválido.";
    } else {
        try {
            $stmt = $pdo->prepare("UPDATE tags SET nome = ?, tipo = ? WHERE id = ?");
            $stmt->execute([$nome, $tipo, $id]);

            $_SESSION['sucesso'] = "Tag atualizada com sucesso.";
            header("Location: gerenciar_tags.php");
            exit;
        } catch (PDOException $e) {
            $_SESSION['erro'] = "Erro ao atualizar tag: " . $e->getMessage();
        }
    }
}
?>

<div class="container py-4">
    <h2 class="mb-4">✏️ Editar Tag</h2>

    <?php if (!empty($_SESSION['erro'])): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($_SESSION['erro']); unset($_SESSION['erro']); ?></div>
    <?php endif; ?>

    <form method="post" class="card shadow-sm p-4">
        <div class="mb-3">
            <label for="nome" class="form-label">Nome da Tag</label>
            <input type="text" name="nome" id="nome" class="form-control" value="<?= htmlspecialchars($tag['nome']) ?>" required>
        </div>

        <div class="mb-3">
            <label for="tipo" class="form-label">Tipo</label>
            <select name="tipo" id="tipo" class="form-select" required>
                <option value="">Selecione o tipo</option>
                <?php foreach ($tipos_validos as $tipo_opcao): ?>
                    <option value="<?= htmlspecialchars($tipo_opcao) ?>" <?= $tag['tipo'] === $tipo_opcao ? 'selected' : '' ?>>
                        <?= ucfirst($tipo_opcao) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <button type="submit" class="btn btn-primary">
            <i class="bi bi-save me-1"></i> Atualizar
        </button>
        <a href="gerenciar_tags.php" class="btn btn-outline-secondary ms-2">
            <i class="bi bi-arrow-left"></i> Voltar
        </a>
    </form>
</div>

<?php require_once __DIR__ . '/../../../backend/includes/footer.php'; ?>
