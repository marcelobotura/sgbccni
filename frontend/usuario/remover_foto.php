<?php
// Caminho: frontend/usuario/remover_foto.php

define('BASE_PATH', dirname(__DIR__, 2) . '/backend');
require_once BASE_PATH . '/config/config.php';
require_once BASE_PATH . '/includes/session.php';

exigir_login('usuario');

$id = $_SESSION['usuario_id'];
$foto_atual = $_SESSION['usuario_foto'] ?? null;
$caminho_arquivo = dirname(__DIR__, 2) . '/storage/uploads/perfis/' . $foto_atual;

// Remove a imagem do disco se existir
if (!empty($foto_atual) && file_exists($caminho_arquivo)) {
    unlink($caminho_arquivo);
}

// Atualiza o banco de dados
$stmt = $pdo->prepare("UPDATE usuarios SET foto = NULL WHERE id = ?");
$stmt->execute([$id]);

// Atualiza a sessão
$_SESSION['usuario_foto'] = null;
$_SESSION['sucesso'] = "Foto de perfil removida com sucesso.";

header("Location: editar_perfil.php");
exit;
