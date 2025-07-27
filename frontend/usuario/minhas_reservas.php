<?php
require_once __DIR__ . '/../../../backend/config/config.php';
require_once BASE_PATH . '/backend/includes/verifica_usuario.php';

$usuario_id = $_SESSION['usuario_id'];

$stmt = $pdo->prepare("SELECT r.*, l.titulo FROM reservas r 
    JOIN livros l ON r.livro_id = l.id 
    WHERE r.usuario_id = ? ORDER BY r.data_reserva DESC");
$stmt->execute([$usuario_id]);
$reservas = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Minhas Reservas - <?= NOME_SISTEMA ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="<?= URL_BASE ?>frontend/assets/css/base/base.css">
</head>
<body>
<div class="container mt-4">
    <h2 class="mb-3">📚 Minhas Reservas</h2>

    <?php if (isset($_SESSION['sucesso'])): ?>
        <div class="alert alert-success"><?= $_SESSION['sucesso']; unset($_SESSION['sucesso']); ?></div>
    <?php endif; ?>
    <?php if (isset($_SESSION['erro'])): ?>
        <div class="alert alert-danger"><?= $_SESSION['erro']; unset($_SESSION['erro']); ?></div>
    <?php endif; ?>

    <?php if (count($reservas) === 0): ?>
        <div class="alert alert-info">Você ainda não fez nenhuma reserva.</div>
    <?php else: ?>
        <table class="table table-hover">
            <thead class="table-dark">
                <tr>
                    <th>Título do Livro</th>
                    <th>Data da Reserva</th>
                    <th>Status</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($reservas as $res): ?>
                <tr>
                    <td><?= htmlspecialchars($res['titulo']) ?></td>
                    <td><?= date('d/m/Y H:i', strtotime($res['data_reserva'])) ?></td>
                    <td><span class="badge bg-secondary"><?= ucfirst($res['status']) ?></span></td>
                    <td>
                        <?php if ($res['status'] === 'ativa'): ?>
                            <a href="cancelar_reserva.php?id=<?= $res['id'] ?>" 
                               class="btn btn-sm btn-outline-danger" 
                               onclick="return confirm('Tem certeza que deseja cancelar esta reserva?')">
                               Cancelar
                            </a>
                        <?php else: ?>
                            <span class="text-muted">-</span>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>
</body>
</html>
