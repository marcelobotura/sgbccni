<?php
require_once __DIR__ . '/../vendor/autoload.php';
define('BASE_PATH', __DIR__ . '/../app_backend');
require_once BASE_PATH . '/config/config.php';

$isbn = $_GET['isbn'] ?? '';
if (!$isbn) exit('ISBN não informado');

$stmt = $conn->prepare("SELECT * FROM livros WHERE isbn = ?");
$stmt->bind_param("s", $isbn);
$stmt->execute();
$livro = $stmt->get_result()->fetch_assoc();

use Dompdf\Dompdf;
$pdf = new Dompdf();

$html = "<h1>{$livro['titulo']}</h1>";
$html .= "<p><strong>Autor:</strong> {$livro['autor']}</p>";
$html .= "<p><strong>Ano:</strong> {$livro['ano']}</p>";
$html .= "<p><strong>Editora:</strong> {$livro['editora']}</p>";
$html .= "<p><strong>Descrição:</strong><br>{$livro['descricao']}</p>";

$pdf->loadHtml($html);
$pdf->render();
$pdf->stream("livro_{$isbn}.pdf", ["Attachment" => false]);
exit;
