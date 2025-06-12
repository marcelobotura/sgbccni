<?php
session_start();
require_once __DIR__ . '/../../backend/config/config.php';
require_once __DIR__ . '/../../backend/includes/session.php';

exigir_login('usuario');

$usuario_id = $_SESSION['usuario_id'];
$livro_id = intval($_POST['livro_id'] ?? 0);

if ($livro_id > 0) {
    try {
        $stmt = $conn->prepare("UPDATE livros_usuarios 
                                SET status = 'lido', data_leitura = NOW() 
                                WHERE usuario_id = :usuario_id AND livro_id = :livro_id");
        $stmt->bindParam(':usuario_id', $usuario_id, PDO::PARAM_INT);
        $stmt->bindParam(':livro_id', $livro_id, PDO::PARAM_INT);
        $stmt->execute();
    } catch (PDOException $e) {
        // Opcional: registrar erro para debug
        $_SESSION['erro'] = "Erro ao marcar como lido: " . $e->getMessage();
    }
}

// Redireciona para o histórico de leitura
header("Location: historico.php");
exit;
