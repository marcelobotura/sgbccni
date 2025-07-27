<?php
require_once __DIR__ . '/../config/config.php';
require_once BASE_PATH . '/vendor/autoload.php';

use Dompdf\Dompdf;

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

ob_start();
?>

<h2>📄 Relatório de Empréstimos</h2>
<table border="1" width="100%" cellspacing="0" cellpadding="4">
  <thead>
    <tr>
      <th>Livro</th>
      <th>Usuário</th>
      <th>Data Empréstimo</th>
      <th>Prevista</th>
      <th>Devolução</th>
      <th>Status</th>
      <th>Multa</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($emprestimos as $e): ?>
    <tr>
      <td><?= htmlspecialchars($e['titulo']) ?></td>
      <td><?= htmlspecialchars($e['usuario_nome']) ?></td>
      <td><?= date('d/m/Y', strtotime($e['data_emprestimo'])) ?></td>
      <td><?= date('d/m/Y', strtotime($e['data_prevista_devolucao'])) ?></td>
      <td><?= $e['data_devolucao'] ? date('d/m/Y', strtotime($e['data_devolucao'])) : '-' ?></td>
      <td><?= ucfirst($e['status']) ?></td>
      <td>R$ <?= number_format($e['multa'], 2, ',', '.') ?></td>
    </tr>
    <?php endforeach; ?>
  </tbody>
</table>

<?php
$html = ob_get_clean();
$dompdf = new Dompdf();
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'landscape');
$dompdf->render();
$dompdf->stream("relatorio_emprestimos.pdf", ["Attachment" => false]);
exit;
