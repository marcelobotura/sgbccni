<?php
require_once __DIR__ . '/../../../backend/config/config.php';
require_once __DIR__ . '/../../../backend/includes/session.php';
require_once __DIR__ . '/../../../backend/includes/protect_admin.php';
require_once __DIR__ . '/../../../vendor/autoload.php';

use Dompdf\Dompdf;
use Dompdf\Options;

$options = new Options();
$options->set('defaultFont', 'DejaVu Sans');
$dompdf = new Dompdf($options);

$stmt = $pdo->query("SELECT nome, email, mensagem, data_envio FROM mensagens_contato ORDER BY data_envio DESC");
$mensagens = $stmt->fetchAll(PDO::FETCH_ASSOC);

$html = '
<style>
    table { border-collapse: collapse; width: 100%; }
    th, td { border: 1px solid #ccc; padding: 6px; font-size: 12px; }
    th { background-color: #f2f2f2; }
</style>
<h2>📩 Mensagens de Contato</h2>
<table>
    <thead>
        <tr>
            <th>Nome</th>
            <th>Email</th>
            <th>Mensagem</th>
            <th>Data</th>
        </tr>
    </thead>
    <tbody>';

foreach ($mensagens as $msg) {
    $html .= '<tr>
        <td>' . htmlspecialchars($msg['nome']) . '</td>
        <td>' . htmlspecialchars($msg['email']) . '</td>
        <td>' . nl2br(htmlspecialchars($msg['mensagem'])) . '</td>
        <td>' . date('d/m/Y H:i', strtotime($msg['data_envio'])) . '</td>
    </tr>';
}

$html .= '</tbody></table>';

$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();
$dompdf->stream("mensagens_contato.pdf", ["Attachment" => true]);
exit;
