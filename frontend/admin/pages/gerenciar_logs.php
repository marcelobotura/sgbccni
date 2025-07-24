<?php
require_once __DIR__ . '/../../../backend/config/config.php';
require_once __DIR__ . '/../../../backend/includes/header.php';

require_once __DIR__ . '/../../../backend/includes/protect_admin.php';

exigir_login('admin');
?>

<div class="container py-4">
    <h2 class="mb-4">📊 Central de Logs do Sistema</h2>

    <p class="mb-4">Aqui você pode acessar os registros de ações importantes realizadas no sistema, como redefinições de senha, tentativas de login e atividades dos usuários.</p>

    <div class="row">
        <div class="col-md-4 mb-3">
            <div class="card border-primary h-100">
                <div class="card-body">
                    <h5 class="card-title">🔐 Logs de Redefinição de Senha</h5>
                    <p class="card-text">Registros de tentativas de redefinição de senha por e-mail, IP e data.</p>
                    <a href="logs_redefinicao.php" class="btn btn-outline-primary w-100">Ver Logs</a>
                </div>
            </div>
        </div>

        <div class="col-md-4 mb-3">
            <div class="card border-success h-100">
                <div class="card-body">
                    <h5 class="card-title">👤 Logs de Login</h5>
                    <p class="card-text">Histórico de tentativas de login bem-sucedidas ou falhas, por IP e usuário.</p>
                    <a href="logs_login.php" class="btn btn-outline-success w-100">Ver Logs</a>
                </div>
            </div>
        </div>

        <div class="col-md-4 mb-3">
            <div class="card border-info h-100">
                <div class="card-body">
                    <h5 class="card-title">📝 Logs de Atividade</h5>
                    <p class="card-text">Registros de ações feitas por administradores e usuários no sistema.</p>
                    <a href="logs_atividade.php" class="btn btn-outline-info w-100">Ver Logs</a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../../../backend/includes/footer.php'; ?>
