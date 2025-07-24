<?php
// Caminho: backend/controllers/midias/buscar_info_midia.php
require_once '../../config/config.php';
header('Content-Type: application/json');

// 📥 Recebe URL
$url = trim($_POST['url'] ?? '');
if (empty($url)) {
  echo json_encode(['sucesso' => false, 'erro' => 'URL ausente.']);
  exit;
}

// 🔰 Inicializa dados
$dados = [
  'titulo' => '',
  'descricao' => '',
  'plataforma' => '',
  'autor' => '',
  'capa' => '',
];

// ⚙️ Carrega HTML da URL
libxml_use_internal_errors(true);
$doc = new DOMDocument();
$html = @file_get_contents($url);

if ($html && @$doc->loadHTML($html)) {
  $xpath = new DOMXPath($doc);

  // 🔍 Meta OG e author
  $metaTags = [
    'og:title' => 'titulo',
    'og:description' => 'descricao',
    'og:image' => 'capa',
    'og:site_name' => 'plataforma',
    'author' => 'autor',
  ];

  foreach ($metaTags as $meta => $campo) {
    $query = "//meta[@property='$meta'] | //meta[@name='$meta']";
    $nodes = $xpath->query($query);
    if ($nodes->length > 0) {
      $dados[$campo] = $nodes[0]->getAttribute('content');
    }
  }

  // 🧾 Fallback: <title>
  if (empty($dados['titulo'])) {
    $titleNode = $doc->getElementsByTagName('title')->item(0);
    $dados['titulo'] = $titleNode ? trim($titleNode->textContent) : '';
  }
}

// 🎞️ YouTube thumbnail
if (strpos($url, 'youtube.com') !== false || strpos($url, 'youtu.be') !== false) {
  if (preg_match('/(?:v=|\.be\/)([\w-]+)/', $url, $matches)) {
    $videoId = $matches[1];
    if (empty($dados['capa'])) {
      $dados['capa'] = "https://img.youtube.com/vi/$videoId/hqdefault.jpg";
    }
    $dados['plataforma'] = 'YouTube';
  }
}

// 🎧 Spotify fallback
if (strpos($url, 'open.spotify.com/episode/') !== false) {
  $dados['plataforma'] = 'Spotify';
  if (empty($dados['titulo'])) {
    $dados['titulo'] = 'Episódio Spotify';
  }
  if (empty($dados['capa'])) {
    $dados['capa'] = 'https://i.scdn.co/image/ab6765630000ba8a1956d4b71602bb5f8ebc846b';
  }
}

// ✅ Retorna resultado
echo json_encode(['sucesso' => true] + $dados);
