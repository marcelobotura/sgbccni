<?php
// ✅ Arquivo: backend/services/buscar_isbn.php
header('Content-Type: application/json');

$isbn = $_GET['isbn'] ?? '';
if (!$isbn) {
  echo json_encode(['erro' => 'ISBN não fornecido']);
  exit;
}

// 📁 Diretório de cache
$cacheDir = __DIR__ . '/../../cache_isbn';
if (!is_dir($cacheDir)) {
  mkdir($cacheDir, 0777, true);
}
$cacheFile = "$cacheDir/$isbn.json";

// 🔄 Usa cache se existir e for recente (24h)
if (file_exists($cacheFile) && time() - filemtime($cacheFile) < 86400) {
  echo file_get_contents($cacheFile);
  exit;
}

// 🔧 Função para buscar conteúdo externo via cURL (mais confiável)
function getUrl($url) {
  $ch = curl_init($url);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // Opcional: desativa verificação SSL
  $data = curl_exec($ch);
  curl_close($ch);
  return $data;
}

// 🔍 Busca na API do Google Books
function buscarGoogleBooks($isbn) {
  $url = "https://www.googleapis.com/books/v1/volumes?q=isbn:$isbn";
  $resposta = getUrl($url);
  if (!$resposta) return null;

  $dados = json_decode($resposta, true);
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

// 🔍 Busca na OpenLibrary (fallback)
function buscarOpenLibrary($isbn) {
  $url = "https://openlibrary.org/api/books?bibkeys=ISBN:$isbn&format=json&jscmd=data";
  $resposta = getUrl($url);
  if (!$resposta) return null;

  $dados = json_decode($resposta, true);
  $info = $dados["ISBN:$isbn"] ?? null;
  if ($info) {
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

// 📚 Tenta buscar na API (Google primeiro, depois OpenLibrary)
$resultado = buscarGoogleBooks($isbn) ?? buscarOpenLibrary($isbn);

if ($resultado) {
  file_put_contents($cacheFile, json_encode($resultado, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
  echo json_encode($resultado, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
} else {
  echo json_encode(['erro' => 'Livro não encontrado para o ISBN informado.']);
}
