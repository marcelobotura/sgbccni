<?php
require_once __DIR__ . '/../../../backend/config/config.php';
require_once __DIR__ . '/../../../backend/includes/header.php';
require_once __DIR__ . '/../../../backend/includes/menu.php';
require_once __DIR__ . '/../../../backend/includes/protect_admin.php';

exigir_login('admin');

// 🔍 Carregar configurações atuais (usando PDO corretamente)
try {
    $stmt = $pdo->prepare("SELECT chave, valor FROM configuracoes");
    $stmt->execute();
    $config = [];
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $config[$row['chave']] = $row['valor'];
    }
} catch (PDOException $e) {
    $_SESSION['erro'] = "Erro ao carregar configurações: " . $e->getMessage();
    $config = [];
}
?>

<div class="container py-4">
    <h2 class="mb-4">⚙️ Configurações do Sistema</h2>

    <?php if (!empty($_SESSION['sucesso'])): ?>
        <div class="alert alert-success"><?= htmlspecialchars($_SESSION['sucesso']); unset($_SESSION['sucesso']); ?></div>
    <?php elseif (!empty($_SESSION['erro'])): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($_SESSION['erro']); unset($_SESSION['erro']); ?></div>
    <?php endif; ?>

    <form action="../../../backend/controllers/configuracoes/salvar_configuracoes.php" method="POST">

        <div class="mb-3">
            <label class="form-label">Nome da Biblioteca</label>
            <input type="text" name="nome_sistema" class="form-control" value="<?= htmlspecialchars($config['nome_sistema'] ?? '') ?>" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Email de Contato</label>
            <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($config['email'] ?? '') ?>">
        </div>

        <div class="mb-3">
            <label class="form-label">Telefone</label>
            <input type="text" name="telefone" class="form-control" value="<?= htmlspecialchars($config['telefone'] ?? '') ?>">
        </div>

        <div class="mb-3">
            <label class="form-label">Endereço</label>
            <input type="text" name="endereco" class="form-control" value="<?= htmlspecialchars($config['endereco'] ?? '') ?>">
        </div>

        <div class="mb-3">
            <label class="form-label">Descrição da Biblioteca</label>
            <textarea name="descricao" rows="4" class="form-control"><?= htmlspecialchars($config['descricao'] ?? '') ?></textarea>
        </div>

        <div class="mb-3">
            <label class="form-label">URL do Site</label>
            <input type="url" name="url" class="form-control" value="<?= htmlspecialchars($config['url'] ?? '') ?>">
        </div>

        <button type="submit" class="btn btn-success">💾 Salvar Configurações</button>
    </form>
</div>

<?php require_once __DIR__ . '/../../../backend/includes/footer.php'; ?>
