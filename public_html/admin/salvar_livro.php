<?php
define('BASE_PATH', dirname(__DIR__) . '/../app_backend');
require_once BASE_PATH . '/config/config.php';
require_once BASE_PATH . '/includes/session.php';

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
    $stmt = $conn->prepare("INSERT INTO tags (nome, tipo) VALUES (?, ?)");
    $stmt->bind_param("ss", $nome, $tipo);
    $stmt->execute();
    return $stmt->insert_id;
  }
}

$titulo = $_POST['titulo'] ?? '';
$autor_nome = $_POST['autor_nome'] ?? '';
$editora_nome = $_POST['editora_nome'] ?? '';
$categoria_nome = $_POST['categoria_nome'] ?? '';
$formato = $_POST['formato'] ?? 'físico';
$link_leitura = $_POST['link_leitura'] ?? '';
$isbn = trim($_POST['isbn'] ?? '');
$descricao = $_POST['descricao'] ?? '';
$idioma = $_POST['idioma'] ?? '';
$ano = $_POST['ano'] ?? '';
$paginas = $_POST['paginas'] ?? '';

$autor_id = obterOuCriarTag($conn, $autor_nome, 'autor');
$editora_id = obterOuCriarTag($conn, $editora_nome, 'editora');
$categoria_id = obterOuCriarTag($conn, $categoria_nome, 'categoria');

$capa_url = $_POST['capa_url'] ?? '';
$isbn10 = substr($isbn, 0, 10);
$isbn13 = substr($isbn, 0, 13);
$numero_interno = uniqid('LIV-');
$codigo_barras = $isbn ?: rand(1000000000000, 9999999999999);
$qr_code = URL_BASE . "livro.php?isbn=" . urlencode($isbn);

$stmt = $conn->prepare("INSERT INTO livros 
  (titulo, autor_id, editora_id, categoria_id, ano, paginas, idioma, descricao, capa_url,
   isbn, isbn10, isbn13,
   numero_interno, codigo_barras, qr_code,
   formato, link_leitura, tipo, copias_disponiveis, exemplares, visualizacoes, criado_em)
  VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'livro', 1, 1, 0, NOW())");

$stmt->bind_param("siiisssssssssssss", 
  $titulo, $autor_id, $editora_id, $categoria_id, $ano, $paginas, $idioma, $descricao, $capa_url,
  $isbn, $isbn10, $isbn13,
  $numero_interno, $codigo_barras, $qr_code,
  $formato, $link_leitura
);

if ($stmt->execute()) {
  $_SESSION['sucesso'] = "✅ Livro salvo com sucesso!";
} else {
  $_SESSION['erro'] = "Erro: " . $stmt->error;
}
header("Location: cadastrar_livro.php");
exit;
?>
