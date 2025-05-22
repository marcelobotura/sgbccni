<?php
header('Content-Type: application/json');

$isbn = trim($_GET['isbn'] ?? '');

if (!$isbn) {
  echo json_encode(['erro' => 'ISBN não informado']);
  exit;
}

// 🧠 Primeiro tenta buscar no Google Books
$google = @file_get_contents("https://www.googleapis.com/books/v1/volumes?q=isbn:$isbn");
$dados_google = json_decode($google, true);

if (!empty($dados_google['items'][0]['volumeInfo'])) {
  $info = $dados_google['items'][0]['volumeInfo'];
  echo json_encode([
    'fonte'      => 'google',
    'titulo'     => $info['title'] ?? '',
    'autor'      => $info['authors'][0] ?? '',
    'editora'    => $info['publisher'] ?? '',
    'categoria'  => $info['categories'][0] ?? '',
    'descricao'  => $info['description'] ?? '',
    'idioma'     => $info['language'] ?? '',
    'paginas'    => $info['pageCount'] ?? '',
    'ano'        => substr($info['publishedDate'] ?? '', 0, 4),
    'capa'       => $info['imageLinks']['thumbnail'] ?? ''
  ]);
  exit;
}

// 🔄 Se Google falhar, tenta no OpenLibrary
$open = @file_get_contents("https://openlibrary.org/isbn/$isbn.json");
$dados_open = json_decode($open, true);

if (!empty($dados_open['title'])) {
  echo json_encode([
    'fonte'      => 'openlibrary',
    'titulo'     => $dados_open['title'] ?? '',
    'autor'      => $dados_open['by_statement'] ?? '',
    'editora'    => $dados_open['publishers'][0] ?? '',
    'categoria'  => '',
    'descricao'  => '',
    'idioma'     => '',
    'paginas'    => $dados_open['number_of_pages'] ?? '',
    'ano'        => $dados_open['publish_date'] ?? '',
    'capa'       => "https://covers.openlibrary.org/b/isbn/$isbn-L.jpg"
  ]);
  exit;
}

echo json_encode(['erro' => 'Livro não encontrado em nenhuma fonte']);
