<?php
// Caminho: backend/controllers/relatorios/gerar_pdf.php

define('BASE_PATH', dirname(__DIR__, 3));
require_once BASE_PATH . '/config/config.php';
require_once BASE_PATH . '/includes/db.php';

require_once BASE_PATH . '/vendor/autoload.php';

use Dompdf\Dompdf;

$relatorio = $_GET['relatorio'] ?? '';
$titulo = ucfirst(str_replace('_', ' ', $relatorio));

$stmt = null;

switch ($relatorio) {
    case 'favoritos':
        $stmt = $pdo->query("
            SELECT u.nome AS usuario, l.titulo, l.codigo_interno, f.data_leitura
            FROM livros_usuarios f
            JOIN livros l ON f.livro_id = l.id
            JOIN usuarios u ON f.usuario_id = u.id
            WHERE f.status = 'favorito'
            ORDER BY f.data_leitura DESC
        ");
        break;
    case 'categorias':
        $stmt = $pdo->query("
            SELECT t.nome AS categoria, COUNT(l.id) AS total
            FROM livros l
            LEFT JOIN tags t ON l.categoria_id = t.id
            GROUP BY t.nome
            ORDER BY total DESC
        ");
        break;
    case 'editoras':
        $stmt = $pdo->query("
            SELECT t.nome AS editora, COUNT(l.id) AS total
            FROM livros l
            LEFT JOIN tags t ON l.editora_id = t.id
            GROUP BY t.nome
            ORDER BY total DESC
        ");
        break;
    default:
        die("Relatório inválido.");
}

$dados = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Geração do HTML para o PDF
$html = "<h2>Relatório: {$titulo}</h2><table border='1' cellpadding='5'><tr>";
foreach (array_keys($dados[0] ?? []) as $coluna) {
    $html .= "<th>" . htmlspecialchars($coluna) . "</th>";
}
$html .= "</tr>";

foreach ($dados as $linha) {
    $html .= "<tr>";
    foreach ($linha as $valor) {
        $html .= "<td>" . htmlspecialchars($valor) . "</td>";
    }
    $html .= "</tr>";
}
$html .= "</table>";

$dompdf = new Dompdf();
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();

$dompdf->stream("relatorio_{$relatorio}.pdf", ['Attachment' => true]);
exit;
