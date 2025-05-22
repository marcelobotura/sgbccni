<?php
header('Content-Type: application/json');
$isbn = $_GET['isbn'] ?? '';

if (!$isbn) {
  echo json_encode(['erro' => 'ISBN não fornecido']);
  exit;
}

function buscarGoogleBooks($isbn) {
  $url = "https://www.googleapis.com/books/v1/volumes?q=isbn:$isbn";
  $json = file_get_contents($url);
  $dados = json_decode($json, true);

  if (!empty($dados['items'][0])) {
    $info = $dados['items'][0]['volumeInfo'];
    return [
      'titulo'     => $info['title'] ?? '',
      'autor'      => $info['authors'][0] ?? '',
      'editora'    => $info['publisher'] ?? '',
      'ano'        => substr($info['publishedDate'] ?? '', 0, 4),
      'descricao'  => $info['description'] ?? '',
      'paginas'    => $info['pageCount'] ?? '',
      'idioma'     => $info['language'] ?? '',
      'capa'       => $info['imageLinks']['thumbnail'] ?? '',
      'categoria'  => $info['categories'][0] ?? '',
    ];
  }

  return null;
}

function buscarOpenLibrary($isbn) {
  $url = "https://openlibrary.org/api/books?bibkeys=ISBN:$isbn&format=json&jscmd=data";
  $json = file_get_contents($url);
  $dados = json_decode($json, true);
  $chave = "ISBN:$isbn";

  if (!empty($dados[$chave])) {
    $info = $dados[$chave];
    return [
      'titulo'     => $info['title'] ?? '',
      'autor'      => $info['authors'][0]['name'] ?? '',
      'editora'    => $info['publishers'][0]['name'] ?? '',
      'ano'        => $info['publish_date'] ?? '',
      'descricao'  => $info['notes'] ?? '',
      'paginas'    => $info['number_of_pages'] ?? '',
      'idioma'     => '',
      'capa'       => $info['cover']['medium'] ?? '',
      'categoria'  => '',
    ];
  }

  return null;
}

$resultado = buscarGoogleBooks($isbn);

if (!$resultado) {
  $resultado = buscarOpenLibrary($isbn);
}

if ($resultado) {
  echo json_encode($resultado);
} else {
  echo json_encode(['erro' => 'Livro não encontrado']);
}
