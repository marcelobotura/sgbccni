<?php
require_once dirname(__DIR__) . '/config/config.php';
require_once dirname(__DIR__) . '/includes/session.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../../public_html/admin/cadastrar_livro.php');
    exit;
}

// Função auxiliar para buscar ou criar tag
function obterOuCriarTag($conn, $nome, $tipo) {
    $stmt = $conn->prepare("SELECT id FROM tags WHERE nome = ? AND tipo = ?");
    $stmt->bind_param("ss", $nome, $tipo);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($id);
        $stmt->fetch();
        return $id;
    } else {
        $stmt->close();
        $stmt = $conn->prepare("INSERT INTO tags (nome, tipo) VALUES (?, ?)");
        $stmt->bind_param("ss", $nome, $tipo);
        $stmt->execute();
        return $stmt->insert_id;
    }
}

// Dados do formulário
$titulo      = $_POST['titulo'] ?? '';
$autor_nome  = $_POST['autor_nome'] ?? '';
$editora_nome = $_POST['editora_nome'] ?? '';
$categoria_nome = $_POST['categoria_nome'] ?? '';
$ano         = $_POST['ano'] ?? null;
$paginas     = $_POST['paginas'] ?? null;
$idioma      = $_POST['idioma'] ?? '';
$isbn        = $_POST['isbn'] ?? '';
$descricao   = $_POST['descricao'] ?? '';
$formato     = $_POST['formato'] ?? 'físico';
$link_leitura = $_POST['link_leitura'] ?? null;
$capa_url    = $_POST['capa_url'] ?? '';

// Validação básica
if (empty($titulo) || empty($autor_nome)) {
    $_SESSION['erro'] = 'Título e autor são obrigatórios.';
    header('Location: ../../public_html/admin/cadastrar_livro.php');
    exit;
}

// Verifica se ISBN já existe
if (!empty($isbn)) {
    $stmt = $conn->prepare("SELECT id FROM livros WHERE isbn = ?");
    $stmt->bind_param("s", $isbn);
    $stmt->execute();
    $stmt->store_result();
    if ($stmt->num_rows > 0) {
        $_SESSION['erro'] = 'Já existe um livro cadastrado com esse ISBN.';
        header('Location: ../../public_html/admin/cadastrar_livro.php');
        exit;
    }
}

// Inserir ou obter tags
$autor_id    = obterOuCriarTag($conn, $autor_nome, 'autor');
$editora_id  = obterOuCriarTag($conn, $editora_nome, 'editora');
$categoria_id = obterOuCriarTag($conn, $categoria_nome, 'categoria');

// Inserção final do livro
$stmt = $conn->prepare("INSERT INTO livros 
    (titulo, autor_id, editora_id, categoria_id, ano, isbn, paginas, idioma, descricao, tipo, formato, link_digital, capa_url, criado_em)
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())");

$stmt->bind_param(
    "siiiisssssss",
    $titulo, $autor_id, $editora_id, $categoria_id, $ano, $isbn, $paginas,
    $idioma, $descricao, $formato, $formato, $link_leitura, $capa_url
);

if ($stmt->execute()) {
    $_SESSION['sucesso'] = 'Livro cadastrado com sucesso!';
} else {
    $_SESSION['erro'] = 'Erro ao salvar o livro.';
}

header('Location: ../../public_html/admin/cadastrar_livro.php');
exit;
