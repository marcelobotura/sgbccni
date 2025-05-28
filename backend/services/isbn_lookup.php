<?php
header('Content-Type: application/json');

$isbn = preg_replace('/[^0-9Xx]/', '', $_GET['isbn'] ?? '');

if (!$isbn) {
    echo json_encode(['erro' => 'ISBN não fornecido']);
    exit;
}

// Caminho do cache
$cacheDir = __DIR__ . '/../cache/isbn';
$cacheFile = "$cacheDir/{$isbn}.json";

// 🔁 Se existir no cache e não estiver muito velho (ex: 1 dia), retorna
if (file_exists($cacheFile) && (time() - filemtime($cacheFile)) < 86400) {
    echo file_get_contents($cacheFile);
    exit;
}

// 🔎 Funções de busca
function buscarGoogleBooks($isbn) {
    $url = "https://www.googleapis.com/books/v1/volumes?q=isbn:$isbn";
    $context = stream_context_create(['http' => ['timeout' => 5]]);
    $json = @file_get_contents($url, false, $context);
    if (!$json) return null;

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
    $context = stream_context_create(['http' => ['timeout' => 5]]);
    $json = @file_get_contents($url, false, $context);
    if (!$json) return null;

    $dados = json_decode($json, true);
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

// 🧠 Busca com fallback
$dadosLivro = buscarGoogleBooks($isbn) ?? buscarOpenLibrary($isbn);

if ($dadosLivro) {
    // Garante que diretório existe
    if (!is_dir($cacheDir)) mkdir($cacheDir, 0777, true);

    // Salva no cache
    file_put_contents($cacheFile, json_encode($dadosLivro, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));

    echo json_encode($dadosLivro, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
} else {
    echo json_encode(['erro' => 'Livro não encontrado']);
}
