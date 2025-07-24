<?php
// Caminho: backend/controllers/relatorios/gerar_excel.php

define('BASE_PATH', dirname(__DIR__, 3));
require_once BASE_PATH . '/config/config.php';
require_once BASE_PATH . '/includes/db.php';

require_once BASE_PATH . '/vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Csv;

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
