<?php
// ✅ Cabeçalho JSON
header('Content-Type: application/json');

// ✅ Recebe e limpa o ISBN
$isbn = preg_replace('/[^0-9Xx]/', '', $_GET['isbn'] ?? '');
if (!$isbn) {
    echo json_encode(['erro' => 'ISBN não fornecido']);
    exit;
}

// ✅ Diretório de cache
$cacheDir = __DIR__ . '/../../cache_isbn';
if (!is_dir($cacheDir)) {
    mkdir($cacheDir, 0777, true);
}
$cacheFile = "$cacheDir/{$isbn}.json";

// ✅ Verifica cache (24 horas)
if (file_exists($cacheFile) && (time() - filemtime($cacheFile)) < 86400) {
    echo file_get_contents($cacheFile);
    exit;
}

// 🌐 Função para fazer requisição HTTP (curl)
function getUrl($url) {
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    $res = curl_exec($ch);
    curl_close($ch);
    return $res;
}

// 🔢 Função para gerar ISBN-10 a partir de ISBN-13
function gerarISBN10($isbn13) {
    if (strlen($isbn13) != 13 || substr($isbn13, 0, 3) != '978') return '';
    $isbn9 = substr($isbn13, 3, 9);
    $soma = 0;
    for ($i = 0; $i < 9; $i++) {
        $soma += (10 - $i) * (int)$isbn9[$i];
    }
    $resto = 11 - ($soma % 11);
    $digito = ($resto == 10) ? 'X' : (($resto == 11) ? '0' : (string)$resto);
    return $isbn9 . $digito;
}

// 🔍 Busca no Google Books
function buscarGoogleBooks($isbn) {
    $url = "https://www.googleapis.com/books/v1/volumes?q=isbn:$isbn";
    $json = getUrl($url);
    if (!$json) return null;

    $dados = json_decode($json, true);
    if (!empty($dados['items'][0]['volumeInfo'])) {
        $info = $dados['items'][0]['volumeInfo'];

        return [
            'isbn'          => $isbn,
            'isbn10'        => gerarISBN10($isbn),
            'codigo_barras' => $isbn,
            'titulo'        => $info['title'] ?? '',
            'subtitulo'     => $info['subtitle'] ?? '',
            'autor'         => isset($info['authors']) ? implode(', ', $info['authors']) : '',
            'editora'       => $info['publisher'] ?? '',
            'ano'           => substr($info['publishedDate'] ?? '', 0, 4),
            'descricao'     => $info['description'] ?? '',
            'paginas'       => $info['pageCount'] ?? '',
            'idioma'        => $info['language'] ?? '',
            'capa'          => $info['imageLinks']['thumbnail'] ?? '',
            'categoria'     => isset($info['categories']) ? implode(', ', $info['categories']) : '',
            'volume'        => '',
            'edicao'        => isset($info['contentVersion']) ? str_replace('.', '', $info['contentVersion']) : '',
            'fonte'         => 'Google Books'
        ];
    }
    return null;
}

// 🔍 Busca na OpenLibrary (Fallback)
function buscarOpenLibrary($isbn) {
    $url = "https://openlibrary.org/api/books?bibkeys=ISBN:$isbn&format=json&jscmd=data";
    $json = getUrl($url);
    if (!$json) return null;

    $dados = json_decode($json, true);
    $info = $dados["ISBN:$isbn"] ?? null;
    if ($info) {
        return [
            'isbn'          => $isbn,
            'isbn10'        => gerarISBN10($isbn),
            'codigo_barras' => $isbn,
            'titulo'        => $info['title'] ?? '',
            'subtitulo'     => '',
            'autor'         => isset($info['authors'][0]['name']) ? $info['authors'][0]['name'] : '',
            'editora'       => isset($info['publishers'][0]['name']) ? $info['publishers'][0]['name'] : '',
            'ano'           => $info['publish_date'] ?? '',
            'descricao'     => is_array($info['notes']) ? implode(' ', $info['notes']) : ($info['notes'] ?? ''),
            'paginas'       => $info['number_of_pages'] ?? '',
            'idioma'        => '',
            'capa'          => $info['cover']['medium'] ?? '',
            'categoria'     => '',
            'volume'        => '',
            'edicao'        => '',
            'fonte'         => 'OpenLibrary'
        ];
    }
    return null;
}

// 🚀 Executa a busca
$dadosLivro = buscarGoogleBooks($isbn) ?? buscarOpenLibrary($isbn);

if ($dadosLivro) {
    file_put_contents($cacheFile, json_encode($dadosLivro, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
    echo json_encode($dadosLivro, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
} else {
    echo json_encode(['erro' => 'Livro não encontrado para o ISBN informado.']);
}
?>
