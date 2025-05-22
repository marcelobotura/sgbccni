
// ✅ Arquivo: backend/controllers/livros/atualizar_livro.php
<?php
require_once __DIR__ . '/../../../config/config.php';
require_once __DIR__ . '/../../../includes/session.php';

exigir_login('admin');

$id           = $_POST['id'] ?? null;
$titulo       = trim($_POST['titulo'] ?? '');
isbn          = trim($_POST['isbn'] ?? '');
descricao     = trim($_POST['descricao'] ?? '');
tipo          = $_POST['tipo'] ?? 'físico';
$formato      = $_POST['formato'] ?? 'PDF';
$link_digital = trim($_POST['link_digital'] ?? '');

if (!$id || !$titulo || !$isbn) {
    $_SESSION['erro'] = "Preencha os campos obrigatórios.";
    header("Location: " . URL_BASE . "admin/pages/editar_livro.php?id=$id");
    exit;
}

// Upload de nova capa, se houver
$capa_local = null;
if (!empty($_FILES['capa']['name'])) {
    $permitidos = ['image/jpeg', 'image/png', 'image/webp'];
    $tipo_img = mime_content_type($_FILES['capa']['tmp_name']);

    if (!in_array($tipo_img, $permitidos)) {
        $_SESSION['erro'] = "Formato de imagem inválido.";
        header("Location: " . URL_BASE . "admin/pages/editar_livro.php?id=$id");
        exit;
    }

    $ext = pathinfo($_FILES['capa']['name'], PATHINFO_EXTENSION);
    $novo_nome = uniqid('capa_', true) . '.' . $ext;
    $destino = __DIR__ . '/../../../uploads/capas/' . $novo_nome;

    if (move_uploaded_file($_FILES['capa']['tmp_name'], $destino)) {
        $capa_local = 'uploads/capas/' . $novo_nome;
    } else {
        $_SESSION['erro'] = "Erro ao salvar a imagem.";
        header("Location: " . URL_BASE . "admin/pages/editar_livro.php?id=$id");
        exit;
    }
}

$query = "UPDATE livros SET titulo=?, isbn=?, descricao=?, tipo=?, formato=?, link_digital=?";
$params = [$titulo, $isbn, $descricao, $tipo, $formato, $link_digital];
$types  = "ssssss";

if ($capa_local) {
    $query .= ", capa_local=?";
    $params[] = $capa_local;
    $types .= "s";
}

$query .= " WHERE id=?";
$params[] = $id;
$types .= "i";

$stmt = $conn->prepare($query);
$stmt->bind_param($types, ...$params);

if ($stmt->execute()) {
    $_SESSION['sucesso'] = "Livro atualizado com sucesso!";
} else {
    $_SESSION['erro'] = "Erro ao atualizar: " . $stmt->error;
}

header("Location: " . URL_BASE . "admin/pages/editar_livro.php?id=$id");
exit;

