<?php
require_once __DIR__ . '/../../../backend/config/config.php';
require_once __DIR__ . '/../../../backend/includes/session.php';
require_once __DIR__ . '/../../../backend/includes/protect_master.php';
require_once __DIR__ . '/../../../backend/includes/header.php';
require_once __DIR__ . '/../../../backend/includes/menu.php';

exigir_master();

$backupFile = BASE_PATH . '/storage/backups/sgbccni_backup_' . date('Ymd_His') . '.sql';
$command = "mysqldump -h " . DB_HOST . " -u " . DB_USER . " -p'" . DB_PASS . "' " . DB_NAME . " > $backupFile";

exec($command, $output, $result);

?>

<div class="container py-4">
    <h2 class="mb-4">📦 Backup do Banco de Dados</h2>

    <?php if ($result === 0): ?>
        <div class="alert alert-success">
            ✅ Backup gerado com sucesso: <code><?= basename($backupFile) ?></code>
        </div>
    <?php else: ?>
        <div class="alert alert-danger">
            ❌ Erro ao gerar backup.
        </div>
    <?php endif; ?>
</div>

<?php require_once __DIR__ . '/../../../backend/includes/footer.php'; ?>
