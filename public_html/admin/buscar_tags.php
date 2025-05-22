<?php
define('BASE_PATH', dirname(__DIR__) . '/../app_backend');
require_once BASE_PATH . '/config/config.php';

$tipo = $_GET['tipo'] ?? '';
$termo = $_GET['q'] ?? '';

$stmt = $conn->prepare("SELECT id, nome FROM tags WHERE tipo = ? AND nome LIKE CONCAT('%', ?, '%') LIMIT 10");
$stmt->bind_param("ss", $tipo, $termo);
$stmt->execute();
$result = $stmt->get_result();

$dados = [];
while ($row = $result->fetch_assoc()) {
  $dados[] = ['id' => $row['id'], 'text' => $row['nome']];
}

echo json_encode(['results' => $dados]);
?>
