<?php
header('Content-Type: application/xml');
require_once __DIR__ . '/../backend/config/config.php';

$dominio = 'http://localhost/sgbccni';

function gerarSlug(string $texto): string {
    $texto = html_entity_decode($texto, ENT_QUOTES, 'UTF-8');
    $texto = iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $texto);
    $texto = strtolower($texto);
    $texto = preg_replace('/[^a-z0-9]+/', '-', $texto);
    return trim($texto, '-');
}

$xml = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
$xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";

// Página inicial
$xml .= "<url>\n";
$xml .= "<loc>" . htmlspecialchars("$dominio/index.php", ENT_XML1) . "</loc>\n";
$xml .= "<lastmod>" . date('Y-m-d') . "</lastmod>\n";
$xml .= "<changefreq>daily</changefreq>\n";
$xml .= "<priority>1.0</priority>\n";
$xml .= "</url>\n";

// Páginas fixas
$paginasFixas = ['catalogo.php', 'contato.php', 'diagnostico.php', 'sobre.php'];
foreach ($paginasFixas as $pagina) {
    $url = "$dominio/$pagina";
    $xml .= "<url>\n";
    $xml .= "<loc>" . htmlspecialchars($url, ENT_XML1) . "</loc>\n";
    $xml .= "<lastmod>" . date('Y-m-d') . "</lastmod>\n";
    $xml .= "<changefreq>monthly</changefreq>\n";
    $xml .= "<priority>0.6</priority>\n";
    $xml .= "</url>\n";
}

// Livros (todos públicos)
try {
    $stmt = $pdo->query("SELECT id, titulo FROM livros");
    while ($row = $stmt->fetch()) {
        $slug = gerarSlug($row['titulo']);
        $url = "$dominio/ver_livro.php?id={$row['id']}&slug=$slug";
        $xml .= "<url>\n";
        $xml .= "<loc>" . htmlspecialchars($url, ENT_XML1) . "</loc>\n";
        $xml .= "<lastmod>" . date('Y-m-d') . "</lastmod>\n";
        $xml .= "<changefreq>weekly</changefreq>\n";
        $xml .= "<priority>0.8</priority>\n";
        $xml .= "</url>\n";
    }
} catch (Exception $e) {}

// Mídias ativas
try {
    $stmt = $pdo->query("SELECT id, titulo FROM midias WHERE ativo = 1");
    while ($row = $stmt->fetch()) {
        $slug = gerarSlug($row['titulo']);
        $url = "$dominio/frontend/usuario/detalhes.php?id={$row['id']}&slug=$slug";
        $xml .= "<url>\n";
        $xml .= "<loc>" . htmlspecialchars($url, ENT_XML1) . "</loc>\n";
        $xml .= "<lastmod>" . date('Y-m-d') . "</lastmod>\n";
        $xml .= "<changefreq>monthly</changefreq>\n";
        $xml .= "<priority>0.7</priority>\n";
        $xml .= "</url>\n";
    }
} catch (Exception $e) {}

// Arquivos publicados
try {
    $stmt = $pdo->query("SELECT id, titulo FROM arquivos WHERE publicado = 1");
    while ($row = $stmt->fetch()) {
        $slug = gerarSlug($row['titulo']);
        $url = "$dominio/frontend/admin/pages/ver_arquivo.php?id={$row['id']}&slug=$slug";
        $xml .= "<url>\n";
        $xml .= "<loc>" . htmlspecialchars($url, ENT_XML1) . "</loc>\n";
        $xml .= "<lastmod>" . date('Y-m-d') . "</lastmod>\n";
        $xml .= "<changefreq>monthly</changefreq>\n";
        $xml .= "<priority>0.5</priority>\n";
        $xml .= "</url>\n";
    }
} catch (Exception $e) {}

// Usuários com perfil público
try {
    $stmt = $pdo->query("SELECT id, nome FROM usuarios WHERE visibilidade = 'publico'");
    while ($row = $stmt->fetch()) {
        $slug = gerarSlug($row['nome']);
        $url = "$dominio/frontend/usuario/ver_perfil.php?id={$row['id']}&slug=$slug";
        $xml .= "<url>\n";
        $xml .= "<loc>" . htmlspecialchars($url, ENT_XML1) . "</loc>\n";
        $xml .= "<lastmod>" . date('Y-m-d') . "</lastmod>\n";
        $xml .= "<changefreq>monthly</changefreq>\n";
        $xml .= "<priority>0.4</priority>\n";
        $xml .= "</url>\n";
    }
} catch (Exception $e) {}

$xml .= "</urlset>";
file_put_contents(__DIR__ . '/sitemap.xml', $xml);
echo $xml;
