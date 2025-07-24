<?php
// Caminho: backend/controllers/arquivos/salvar_arquivo.php

require_once '../../config/config.php';
require_once '../../includes/session.php';

exigir_login('admin');

// 🗂️ Categorias e extensões válidas
$categorias = ['apostilas', 'editais', 'imagens', 'outros'];
$extensoes_permitidas = ['pdf', 'docx', 'xlsx', 'jpg', 'jpeg', 'png', 'mp4', 'mp3', 'zip', 'rar'];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['arquivo'])) {
    $categoria = $_POST['categoria'] ?? '';
    $arquivo = $_FILES['arquivo'];

    // Validação da categoria
    if (!in_array($categoria, $categorias)) {
        $_SESSION['erro'] = 'Categoria inválida.';
        header('Location: ../../frontend/admin/pages/gerenciar_arquivos.php');
        exit;
    }

    // Validação do arquivo
    if ($arquivo['error'] !== UPLOAD_ERR_OK) {
        $_SESSION['erro'] = 'Erro no envio do arquivo.';
        header('Location: ../../frontend/admin/pages/gerenciar_arquivos.php');
        exit;
    }

    $nome = basename($arquivo['name']);
    $extensao = strtolower(pathinfo($nome, PATHINFO_EXTENSION));

    if (!in_array($extensao, $extensoes_permitidas)) {
        $_SESSION['erro'] = "Extensão '$extensao' não permitida.";
        header('Location: ../../frontend/admin/pages/gerenciar_arquivos.php');
        exit;
    }

    // 🗂️ Criação do diretório se não existir
    $pasta = BASE_PATH . "/../uploads/arquivos/$categoria";
    if (!is_dir($pasta)) mkdir($pasta, 0777, true);

    $caminho_relativo = "uploads/arquivos/$categoria/$nome";
    $caminho_fisico = BASE_PATH . '/../' . $caminho_relativo;

    // ✅ Move o arquivo para o local correto
    if (!move_uploaded_file($arquivo['tmp_name'], $caminho_fisico)) {
        $_SESSION['erro'] = 'Erro ao mover o arquivo.';
        header('Location: ../../frontend/admin/pages/gerenciar_arquivos.php');
        exit;
    }

    // 📥 Salvar no banco
    $tamanho = filesize($caminho_fisico);

    $stmt = $pdo->prepare("INSERT INTO arquivos (nome, categoria, caminho, extensao, tamanho) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$nome, $categoria, $caminho_relativo, $extensao, $tamanho]);

    $_SESSION['sucesso'] = 'Arquivo salvo com sucesso.';
    header("Location: ../../frontend/admin/pages/gerenciar_arquivos.php?categoria=$categoria");
    exit;
} else {
    $_SESSION['erro'] = 'Requisição inválida.';
    header('Location: ../../frontend/admin/pages/gerenciar_arquivos.php');
    exit;
}
