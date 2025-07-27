<?php
// Caminho: backend/controllers/usuarios/alterar_tipo.php
require_once '../../config/config.php';
require_once '../../includes/verifica_admin.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario_id = (int) ($_POST['usuario_id'] ?? 0);
    $novo_tipo = $_POST['novo_tipo'] ?? 'usuario';

    if (!in_array($novo_tipo, ['usuario', 'admin', 'master'])) {
        header("Location: ../../../frontend/admin/pages/gerenciar_usuarios.php?erro=tipo_invalido");
        exit;
    }

    try {
        $stmt = $pdo->prepare("UPDATE usuarios SET tipo = ? WHERE id = ?");
        $stmt->execute([$novo_tipo, $usuario_id]);
        header("Location: ../../../frontend/admin/pages/gerenciar_usuarios.php?sucesso=1");
        exit;
    } catch (PDOException $e) {
        header("Location: ../../../frontend/admin/pages/gerenciar_usuarios.php?erro=db");
        exit;
    }
}
