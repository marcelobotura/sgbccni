<?php
require_once __DIR__ . '/../../../config/config.php';
require_once __DIR__ . '/../../../includes/session.php';
exigir_login('admin');

// 📥 Recebe e sanitiza os dados
$id              = $_POST['id'] ?? null;
$titulo          = trim($_POST['titulo'] ?? '');
$isbn            = trim($_POST['isbn'] ?? '');
$descricao       = trim($_POST['descricao'] ?? '');
$tipo            = $_POST['tipo'] ?? 'físico';
$formato         = $_POST['formato'] ?? 'PDF';
$link_digital    = trim($_POST['link_digital'] ?? '');
$volume          = trim($_POST['volume'] ?? '');
$edicao          = trim($_POST['edicao'] ?? '');
$codigo_interno  = trim($_POST['codigo_interno'] ?? '');

$autor_id        = is_numeric($_POST['autor_id'] ?? '') ? (int)$_POST['autor_id'] : null;
$editora_id      = is_numeric($_POST['editora_id'] ?? '') ? (int)$_POST['editora_id'] : null;
$categoria_id    = is_numeric($_POST['categoria_id'] ?? '') ? (int)$_POST['categoria_id'] : null;

// 🚫 Validação
if (!$id || !$titulo || !$isbn || !$codigo_interno) {
    $_SESSION['erro'] = "Preencha todos os campos obrigatórios.";
    header("Location: " . URL_BASE . "frontend/admin/pages/editar_livro.php?id=$id");
    exit;
}

// 🖼️ Upload de nova capa (opcional)
$capa_url = null;
if (!empty($_FILES['capa']['name']) && is_uploaded_file($_FILES['capa']['tmp_name'])) {
    $permitidos = ['image/jpeg', 'image/png', 'image/webp'];
    $tipo_arquivo = mime_content_type($_FILES['capa']['tmp_name']);

    if (!in_array($tipo_arquivo, $permitidos)) {
        $_SESSION['erro'] = "Formato de imagem inválido.";
        header("Location: " . URL_BASE . "frontend/admin/pages/editar_livro.php?id=$id");
        exit;
    }

    $ext = strtolower(pathinfo($_FILES['capa']['name'], PATHINFO_EXTENSION));
    $novo_nome = uniqid('capa_', true) . '.' . $ext;
    $destino = __DIR__ . '/../../../uploads/capas/' . $novo_nome;

    if (!is_dir(dirname($destino))) {
        mkdir(dirname($destino), 0755, true);
    }

    if (move_uploaded_file($_FILES['capa']['tmp_name'], $destino)) {
        $capa_url = 'uploads/capas/' . $novo_nome;
    } else {
        $_SESSION['erro'] = "Erro ao salvar a nova capa.";
        header("Location: " . URL_BASE . "frontend/admin/pages/editar_livro.php?id=$id");
        exit;
    }
}

// 🛠️ Monta query dinamicamente
$query = "UPDATE livros SET 
    titulo = ?, isbn = ?, descricao = ?, tipo = ?, formato = ?, link_digital = ?, 
    volume = ?, edicao = ?, codigo_interno = ?, 
    autor_id = ?, editora_id = ?, categoria_id = ?";

$params = [
    $titulo, $isbn, $descricao, $tipo, $formato, $link_digital,
    $volume, $edicao, $codigo_interno,
    $autor_id, $editora_id, $categoria_id
];

$types = "ssssssssiii";

// Se enviou nova capa
if ($capa_url) {
    $query .= ", capa_url = ?";
    $params[] = $capa_url;
    $types .= "s";
}

$query .= " WHERE id = ?";
$params[] = $id;
$types .= "i";

// 🔄 Executa a query
$stmt = $conn->prepare($query);
$stmt->bind_param($types, ...$params);

if ($stmt->execute()) {
    $_SESSION['sucesso'] = "Livro atualizado com sucesso!";
} else {
    error_log("Erro ao atualizar livro ID $id: " . $stmt->error);
    $_SESSION['erro'] = "Erro ao atualizar o livro.";
}

header("Location: " . URL_BASE . "frontend/admin/pages/editar_livro.php?id=$id");
exit;
