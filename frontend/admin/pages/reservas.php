<?php
// Caminho: frontend/admin/pages/reservas.php

if (!defined('BASE_PATH')) {
    define('BASE_PATH', dirname(__DIR__, 3));
}

require_once BASE_PATH . '/backend/config/env.php';
require_once BASE_PATH . '/backend/config/config.php';
require_once BASE_PATH . '/backend/includes/session.php';
require_once BASE_PATH . '/backend/includes/functions_admin.php';

$titulo_pagina = "Reservas de Livros";

// Filtros
$filtro_status = $_GET['status'] ?? '';
$where = '';
$params = [];

if (in_array($filtro_status, ['pendente', 'confirmada', 'cancelada'])) {
    $where = "WHERE r.status = ?";
    $params[] = $filtro_status;
}

// Consulta
try {
    $sql = "
        SELECT r.id, r.data_reserva, r.status,
               u.nome AS nome_usuario,
               l.titulo AS titulo_livro
        FROM reservas r
        JOIN usuarios u ON r.usuario_id = u.id
        JOIN livros l ON r.livro_id = l.id
        $where
        ORDER BY r.data_reserva DESC
    ";
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $reservas = $stmt->fetchAll();
} catch (PDOException $e) {
    $_SESSION['erro'] = "Erro ao buscar reservas: " . $e->getMessage();
    $reservas = [];
}
?>

<!DOCTYPE html>
<html lang="pt-br" data-theme="<?= htmlspecialchars($_COOKIE['tema'] ?? 'light') ?>">
<head>
    <meta charset="UTF-8">
    <title><?= $titulo_pagina ?> - <?= NOME_SISTEMA ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="<?= URL_BASE ?>frontend/assets/css/base/base.css">
</head>
<body>

<?php include BASE_PATH . '/backend/includes/menu.php'; ?>

<div class="container py-4">
    <h1 class="mb-4"><i class="bi bi-calendar-check"></i> <?= $titulo_pagina ?></h1>

    <!-- Mensagens -->
    <?php if (isset($_SESSION['erro'])): ?>
        <div class="alert alert-danger"><?= $_SESSION['erro']; unset($_SESSION['erro']); ?></div>
    <?php endif; ?>

    <?php if (isset($_SESSION['sucesso'])): ?>
        <div class="alert alert-success"><?= $_SESSION['sucesso']; unset($_SESSION['sucesso']); ?></div>
    <?php endif; ?>

    <!-- Filtro de Status -->
    <form method="GET" class="row g-2 mb-3">
        <div class="col-md-4">
            <select name="status" class="form-select" onchange="this.form.submit()">
                <option value="">🔎 Filtrar por status</option>
                <option value="pendente" <?= $filtro_status === 'pendente' ? 'selected' : '' ?>>Pendente</option>
                <option value="confirmada" <?= $filtro_status === 'confirmada' ? 'selected' : '' ?>>Confirmada</option>
                <option value="cancelada" <?= $filtro_status === 'cancelada' ? 'selected' : '' ?>>Cancelada</option>
            </select>
        </div>
    </form>

    <?php if (empty($reservas)): ?>
        <div class="alert alert-info">Nenhuma reserva encontrada.</div>
    <?php else: ?>
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Livro</th>
                        <th>Usuário</th>
                        <th>Data</th>
                        <th>Status</th>
                        <th class="text-center">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($reservas as $reserva): ?>
                        <tr>
                            <td><?= $reserva['id'] ?></td>
                            <td><?= htmlspecialchars($reserva['titulo_livro']) ?></td>
                            <td><?= htmlspecialchars($reserva['nome_usuario']) ?></td>
                            <td><?= date('d/m/Y H:i', strtotime($reserva['data_reserva'])) ?></td>
                            <td>
                                <?php if ($reserva['status'] === 'pendente'): ?>
                                    <span class="badge bg-warning text-dark">Pendente</span>
                                <?php elseif ($reserva['status'] === 'confirmada'): ?>
                                    <span class="badge bg-success">Confirmada</span>
                                <?php elseif ($reserva['status'] === 'cancelada'): ?>
                                    <span class="badge bg-secondary">Cancelada</span>
                                <?php endif; ?>
                            </td>
                            <td class="text-center">
                                <?php if ($reserva['status'] === 'pendente'): ?>
                                    <a href="<?= URL_BASE ?>backend/controllers/reservas/aprovar.php?id=<?= $reserva['id'] ?>" class="btn btn-sm btn-success me-1" title="Confirmar"><i class="bi bi-check-circle"></i></a>
                                    <a href="<?= URL_BASE ?>backend/controllers/reservas/cancelar.php?id=<?= $reserva['id'] ?>" class="btn btn-sm btn-warning me-1" title="Cancelar"><i class="bi bi-x-circle"></i></a>
                                <?php endif; ?>
                                <a href="<?= URL_BASE ?>backend/controllers/reservas/excluir.php?id=<?= $reserva['id'] ?>" class="btn btn-sm btn-danger" title="Excluir" onclick="return confirm('Tem certeza que deseja excluir esta reserva?')">
                                    <i class="bi bi-trash"></i>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
