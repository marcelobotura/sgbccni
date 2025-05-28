<?php
require_once __DIR__ . '/../../../config/config.php';
require_once __DIR__ . '/../../../includes/session.php';

exigir_login('admin');

// 🔄 Coleta e sanitiza os dados
$titulo       = trim($_POST['titulo'] ?? '');
$isbn         = trim($_POST['isbn'] ?? '');
$descricao    = trim($_POST['descricao'] ?? '');
$tipo         = $_POST['tipo'] ?? 'físico';
$formato      = $_POST['formato'] ?? 'PDF';
$link_digital = trim($_POST['link_digital'] ?? null);
$autor_id     = is_numeric($_POST['autor_id'] ?? null) ? (int)$_POST['autor_id'] : null;
$editora_id   = is_numeric($_POST['editora_id'] ?? null) ? (int)$_POST['editora_id'] : null;
$categoria_id = is_numeric($_POST['categoria_id'] ?? null) ? (int)$_POST['categoria_id'] : null;

// 🛡️ Validação
if (empty($titulo) || empty($isbn)) {
    $_SESSION['erro'] = "Título e ISBN são obrigatórios.";
    header("Location: " . URL_BASE . "admin/pages/cadastrar_livro.php");
    exit;
}

// 🚫 ISBN duplicado
$stmt_check = $conn->prepare("SELECT id FROM livros WHERE isbn = ?");
$stmt_check->bind_param("s", $isbn);
$stmt_check->execute();
$stmt_check->store_result();

if ($stmt_check->num_rows > 0) {
    $_SESSION['erro'] = "Este ISBN já está cadastrado.";
    header("Location: " . URL_BASE . "admin/pages/cadastrar_livro.php");
    exit;
}
$stmt_check->close();

// 🖼️ Upload da capa
$capa_local = null;
if (isset($_FILES['capa']) && $_FILES['capa']['error'] === UPLOAD_ERR_OK) {
    $permitidos = ['image/jpeg', 'image/png', 'image/webp'];
    $tipo = mime_content_type($_FILES['capa']['tmp_name']);

    if (!in_array($tipo, $permitidos)) {
        $_SESSION['erro'] = "Formato de imagem não permitido.";
        header("Location: " . URL_BASE . "admin/pages/cadastrar_livro.php");
        exit;
    }

    $ext = pathinfo($_FILES['capa']['name'], PATHINFO_EXTENSION);
    $novo_nome = uniqid('capa_', true) . '.' . $ext;
    $destino = __DIR__ . '/../../../uploads/capas/' . $novo_nome;

    if (move_uploaded_file($_FILES['capa']['tmp_name'], $destino)) {
        $capa_local = 'uploads/capas/' . $novo_nome;
    } else {
        error_log("Falha no upload da imagem.");
        $_SESSION['erro'] = "Erro ao salvar a imagem.";
        header("Location: " . URL_BASE . "admin/pages/cadastrar_livro.php");
        exit;
    }
}

// 💾 Insere no banco
$stmt = $conn->prepare("INSERT INTO livros (
    titulo, isbn, descricao, tipo, formato, link_digital, capa_local, autor_id, editora_id, categoria_id
) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

$stmt->bind_param(
    "sssssssiii",
    $titulo, $isbn, $descricao, $tipo, $formato,
    $link_digital, $capa_local, $autor_id, $editora_id, $categoria_id
);

if ($stmt->execute()) {
    $_SESSION['sucesso'] = "Livro cadastrado com sucesso!";
} else {
    error_log("Erro MySQL: " . $stmt->error);
    $_SESSION['erro'] = "Erro ao cadastrar livro.";
}

header("Location: " . URL_BASE . "admin/pages/cadastrar_livro.php");
exit;
