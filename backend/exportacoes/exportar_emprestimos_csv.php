<?php
require_once __DIR__ . '/../config/config.php';

header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename=relatorio_emprestimos.csv');

$output = fopen('php://output', 'w');
fputcsv($output, ['Livro', 'Usuário', 'Data Empréstimo', 'Prevista', 'Devolução', 'Status', 'Multa']);

$filtro_status = $_GET['status'] ?? '';
$filtro_usuario = $_GET['usuario'] ?? '';
$filtro_data_inicio = $_GET['data_inicio'] ?? '';
$filtro_data_fim = $_GET['data_fim'] ?? '';

$sql = "SELECT e.*, l.titulo, u.nome AS usuario_nome
        FROM emprestimos e
        JOIN livros l ON e.livro_id = l.id
        JOIN usuarios u ON e.usuario_id = u.id
        WHERE 1=1";
$params = [];

if ($filtro_status) {
    $sql .= " AND e.status = ?";
    $params[] = $filtro_status;
}
if ($filtro_usuario) {
    $sql .= " AND u.nome LIKE ?";
    $params[] = "%$filtro_usuario%";
}
if ($filtro_data_inicio) {
    $sql .= " AND e.data_emprestimo >= ?";
    $params[] = $filtro_data_inicio;
}
if ($filtro_data_fim) {
    $sql .= " AND e.data_emprestimo <= ?";
    $params[] = $filtro_data_fim;
}

$sql .= " ORDER BY e.data_emprestimo DESC";
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$emprestimos = $stmt->fetchAll();

foreach ($emprestimos as $e) {
    fputcsv($output, [
        $e['titulo'],
        $e['usuario_nome'],
        date('d/m/Y', strtotime($e['data_emprestimo'])),
        date('d/m/Y', strtotime($e['data_prevista_devolucao'])),
        $e['data_devolucao'] ? date('d/m/Y', strtotime($e['data_devolucao'])) : '-',
        ucfirst($e['status']),
        number_format($e['multa'], 2, ',', '.')
    ]);
}
fclose($output);
exit;
