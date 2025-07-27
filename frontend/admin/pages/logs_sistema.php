<?php
require_once __DIR__ . '/../../../backend/config/config.php';
require_once __DIR__ . '/../../../backend/includes/session.php';
require_once __DIR__ . '/../../../backend/includes/protect_master.php';
require_once __DIR__ . '/../../../backend/includes/header.php';
require_once __DIR__ . '/../../../backend/includes/menu.php';

exigir_master();

$logFile = BASE_PATH . '/storage/logs/system-error.log';
?>

<div class="container py-4">
    <h2 class="mb-4">📄 Logs do Sistema</h2>

    <?php if (file_exists($logFile)): ?>
        <pre class="bg-dark text-white p-3 rounded" style="max-height: 400px; overflow-y: auto; font-size: 0.9em;">
            <?= htmlspecialchars(file_get_contents($logFile)) ?>
        </pre>
    <?php else: ?>
        <div class="alert alert-warning">Nenhum log encontrado.</div>
    <?php endif; ?>
</div>

<?php require_once __DIR__ . '/../../../backend/includes/footer.php'; ?>
