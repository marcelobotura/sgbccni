<?php
// Caminho: backend/controllers/arquivos/salvar_edicao_arquivo.php
require_once '../../config/config.php';
require_once '../../includes/db.php';
require_once '../../includes/session.php';

exigir_login('admin');

// 📥 Dados
$id             = $_POST['id'] ?? 0;
$novo_nome      = trim($_POST['nome'] ?? '');
$nova_categoria = trim($_POST['categoria'] ?? '');
$categorias_validas = ['apostilas', 'editais', 'imagens', 'outros'];

// 🔍 Busca o registro atual
$stmt = $pdo->prepare("SELECT * FROM arquivos WHERE id = ?");
$stmt->execute([$id]);
$arquivo = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$arquivo || !in_array($nova_categoria, $categorias_validas)) {
    $_SESSION['erro'] = 'Arquivo ou categoria inválidos.';
    header("Location: " . URL_BASE . "frontend/admin/pages/gerenciar_arquivos.php");
    exit;
}

$extensao = pathinfo($arquivo['nome'], PATHINFO_EXTENSION);
$novo_nome_completo = (pathinfo($novo_nome, PATHINFO_EXTENSION) === $extensao) 
    ? $novo_nome 
    : $novo_nome . '.' . $extensao;

$antigo_caminho_rel = $arquivo['caminho'];
$antigo_caminho_abs = BASE_PATH . '/../' . $antigo_caminho_rel;
$novo_caminho_rel   = "uploads/arquivos/$nova_categoria/$novo_nome_completo";
$novo_caminho_abs   = BASE_PATH . '/../' . $novo_caminho_rel;

// Move o arquivo se necessário
if ($antigo_caminho_rel !== $novo_caminho_rel) {
    // Cria diretório de destino se não existir
    $novo_diretorio = dirname($novo_caminho_abs);
    if (!is_dir($novo_diretorio)) {
        mkdir($novo_diretorio, 0777, true);
    }

    if (!rename($antigo_caminho_abs, $novo_caminho_abs)) {
        $_SESSION['erro'] = 'Erro ao mover ou renomear o arquivo.';
        header("Location: " . URL_BASE . "frontend/admin/pages/gerenciar_arquivos.php");
        exit;
    }
}

// Atualiza no banco
$stmt = $pdo->prepare("UPDATE arquivos SET nome = ?, categoria = ?, caminho = ? WHERE id = ?");
$stmt->execute([$novo_nome_completo, $nova_categoria, $novo_caminho_rel, $id]);

$_SESSION['sucesso'] = 'Arquivo atualizado com sucesso.';
header("Location: " . URL_BASE . "frontend/admin/pages/gerenciar_arquivos.php?categoria=$nova_categoria");
exit;
