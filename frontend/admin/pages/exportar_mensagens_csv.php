<?php
require_once __DIR__ . '/../../../backend/config/config.php';
require_once __DIR__ . '/../../../backend/includes/session.php';
require_once __DIR__ . '/../../../backend/includes/protect_admin.php';

header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename=mensagens_contato.csv');

$output = fopen('php://output', 'w');
fputcsv($output, ['Nome', 'Email', 'Mensagem', 'Data']);

$stmt = $pdo->query("SELECT nome, email, mensagem, data_envio FROM mensagens_contato ORDER BY data_envio DESC");

while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    fputcsv($output, [$row['nome'], $row['email'], $row['mensagem'], date('d/m/Y H:i', strtotime($row['data_envio']))]);
}

fclose($output);
exit;
