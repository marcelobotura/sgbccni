<?php
require_once __DIR__ . '/../../../backend/config/config.php';
require_once __DIR__ . '/../../../backend/includes/session.php';
require_once __DIR__ . '/../../../backend/includes/protect_master.php';
require_once __DIR__ . '/../../../backend/includes/header.php';
require_once __DIR__ . '/../../../backend/includes/menu.php';

exigir_master();
?>

<div class="container py-4">
    <h2 class="mb-4">⚙️ Configurações do Sistema</h2>
    <p class="text-muted">Página reservada para administradores Master.</p>
    
    <div class="alert alert-info">
        Aqui você poderá futuramente definir parâmetros como nome do sistema, modos de manutenção, permissões globais etc.
    </div>
</div>

<?php require_once __DIR__ . '/../../../backend/includes/footer.php'; ?>
