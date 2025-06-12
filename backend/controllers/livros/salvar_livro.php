<?php
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../includes/session.php';

exigir_login('admin');

// 🔄 Coleta e sanitiza os dados
$titulo         = trim($_POST['titulo'] ?? '');
$isbn           = trim($_POST['isbn'] ?? '');
$descricao      = trim($_POST['descricao'] ?? '');
$tipo           = $_POST['tipo'] ?? 'físico';
$formato        = $_POST['formato'] ?? 'PDF';
$link_digital   = trim($_POST['link_digital'] ?? null);
$volume         = trim($_POST['volume'] ?? '');
$edicao         = trim($_POST['edicao'] ?? '');
$codigo_interno = trim($_POST['codigo_interno'] ?? '');

$autor_id       = is_numeric($_POST['autor_id'] ?? null) ? (int)$_POST['autor_id'] : null;
$editora_id     = is_numeric($_POST['editora_id'] ?? null) ? (int)$_POST['editora_id'] : null;
$categoria_id   = is_numeric($_POST['categoria_id'] ?? null) ? (int)$_POST['categoria_id'] : null;

// 🛡️ Validação
if (empty($titulo) || empty($isbn) || empty($codigo_interno)) {
    $_SESSION['erro'] = "Título, ISBN e Código Interno são obrigatórios.";
    header("Location: " . URL_BASE . "frontend/admin/pages/cadastrar_livro.php");
    exit;
}

// 🚫 Verifica se ISBN já existe
$stmt_check = $conn->prepare("SELECT id FROM livros WHERE isbn = :isbn");
$stmt_check->bindValue(':isbn', $isbn);
$stmt_check->execute();
if ($stmt_check->rowCount() > 0) {
    $_SESSION['erro'] = "Este ISBN já está cadastrado.";
    header("Location: " . URL_BASE . "frontend/admin/pages/cadastrar_livro.php");
    exit;
}

// 🖼️ Upload da capa
$capa_url = null;
if (isset($_FILES['capa']) && $_FILES['capa']['error'] === UPLOAD_ERR_OK) {
    $permitidos = ['image/jpeg', 'image/png', 'image/webp'];
    $tipo_arquivo = mime_content_type($_FILES['capa']['tmp_name']);

    if (!in_array($tipo_arquivo, $permitidos)) {
        $_SESSION['erro'] = "Formato de imagem não permitido.";
        header("Location: " . URL_BASE . "frontend/admin/pages/cadastrar_livro.php");
        exit;
    }

    $ext = pathinfo($_FILES['capa']['name'], PATHINFO_EXTENSION);
    $novo_nome = uniqid('capa_', true) . '.' . $ext;
    $destino = __DIR__ . '/../../../uploads/capas/' . $novo_nome;

    if (!is_dir(dirname($destino))) {
        mkdir(dirname($destino), 0755, true);
    }

    if (move_uploaded_file($_FILES['capa']['tmp_name'], $destino)) {
        $capa_url = 'uploads/capas/' . $novo_nome;
    } else {
        $_SESSION['erro'] = "Erro ao salvar a imagem.";
        header("Location: " . URL_BASE . "frontend/admin/pages/cadastrar_livro.php");
        exit;
    }
}

// 💾 Inserção no banco (PDO)
$stmt = $conn->prepare("INSERT INTO livros (
    titulo, isbn, volume, edicao, codigo_interno,
    descricao, tipo, formato, link_digital, capa_url,
    autor_id, editora_id, categoria_id, disponivel
) VALUES (
    :titulo, :isbn, :volume, :edicao, :codigo_interno,
    :descricao, :tipo, :formato, :link_digital, :capa_url,
    :autor_id, :editora_id, :categoria_id, 1
)");

$sucesso = $stmt->execute([
    ':titulo'         => $titulo,
    ':isbn'           => $isbn,
    ':volume'         => $volume,
    ':edicao'         => $edicao,
    ':codigo_interno' => $codigo_interno,
    ':descricao'      => $descricao,
    ':tipo'           => $tipo,
    ':formato'        => $formato,
    ':link_digital'   => $link_digital,
    ':capa_url'       => $capa_url,
    ':autor_id'       => $autor_id,
    ':editora_id'     => $editora_id,
    ':categoria_id'   => $categoria_id
]);

if ($sucesso) {
    $_SESSION['sucesso'] = "Livro cadastrado com sucesso!";
} else {
    error_log("Erro ao cadastrar livro: " . implode(" | ", $stmt->errorInfo()));
    $_SESSION['erro'] = "Erro ao cadastrar livro.";
}

header("Location: " . URL_BASE . "frontend/admin/pages/cadastrar_livro.php");
exit;
