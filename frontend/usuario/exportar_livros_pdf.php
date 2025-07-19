<?php
require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/../../backend/config/config.php';

use Dompdf\Dompdf;

$isbn = $_GET['isbn'] ?? '';
if (empty($isbn)) {
    die("ISBN não informado.");
}

// 🔍 Buscar livro
$stmt = $pdo->prepare("SELECT * FROM livros WHERE isbn = ?");
$stmt->execute([$isbn]);
$livro = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$livro) {
    die("Livro não encontrado.");
}

// Obter dados das tags
function tag_nome($id, $pdo) {
    if (!$id) return 'N/A';
    $stmt = $pdo->prepare("SELECT nome FROM tags WHERE id = ?");
    $stmt->execute([$id]);
    $tag = $stmt->fetch();
    return $tag ? $tag['nome'] : 'N/A';
}
$autor = tag_nome($livro['autor_id'], $pdo);
$editora = tag_nome($livro['editora_id'], $pdo);
$categoria = tag_nome($livro['categoria_id'], $pdo);

// 📄 HTML para o PDF
$html = "
<h2>📚 Detalhes do Livro</h2>
<strong>Título:</strong> {$livro['titulo']}<br>
<strong>Subtítulo:</strong> {$livro['subtitulo']}<br>
<strong>Autor:</strong> $autor<br>
<strong>Editora:</strong> $editora<br>
<strong>Categoria:</strong> $categoria<br>
<strong>Volume:</strong> {$livro['volume']}<br>
<strong>Edição:</strong> {$livro['edicao']}<br>
<strong>ISBN:</strong> {$livro['isbn']}<br>
<strong>ISBN-10:</strong> {$livro['isbn10']}<br>
<strong>Código Interno:</strong> {$livro['codigo_interno']}<br>
<strong>Tipo:</strong> {$livro['tipo']}<br>
<strong>Formato:</strong> {$livro['formato']}<br>
<strong>Link Digital:</strong> {$livro['link_digital']}<br>
<strong>Descrição:</strong><br><pre>{$livro['descricao']}</pre>
";

$dompdf = new Dompdf();
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();
$dompdf->stream("livro_{$livro['isbn']}.pdf");
