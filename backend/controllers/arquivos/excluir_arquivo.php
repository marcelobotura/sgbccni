<?php
// Caminho: backend/controllers/arquivos/excluir_arquivo.php

require_once '../../config/config.php';
require_once '../../includes/db.php';
require_once '../../includes/session.php';

exigir_login('admin');

$id = $_GET['id'] ?? 0;

if ($id > 0) {
    // Consulta o arquivo
    $stmt = $pdo->prepare("SELECT * FROM arquivos WHERE id = ?");
    $stmt->execute([$id]);
    $arquivo = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($arquivo) {
        $caminho_fisico = BASE_PATH . '/' . $arquivo['caminho'];

        // Apaga do disco
        if (file_exists($caminho_fisico)) {
            unlink($caminho_fisico);
        }

        // Apaga do banco
        $stmt = $pdo->prepare("DELETE FROM arquivos WHERE id = ?");
        $stmt->execute([$id]);

        $_SESSION['sucesso'] = 'Arquivo excluído com sucesso.';
    } else {
        $_SESSION['erro'] = 'Arquivo não encontrado no banco.';
    }
} else {
    $_SESSION['erro'] = 'ID inválido.';
}

header("Location: " . URL_BASE . "frontend/admin/pages/gerenciar_arquivos.php");
exit;
