<?php
// BASE_PATH corrigida para apontar corretamente para 'app_backend'
// Se o arquivo está em public_html/admin/, dirname(__DIR__, 2) sobe para o diretório ACIMA de public_html.
// Assumindo que app_backend está nesse mesmo nível.
define('BASE_PATH', dirname(__DIR__, 2) . '/app_backend');
require_once BASE_PATH . '/config/config.php';

$tipo = $_GET['tipo'] ?? '';
$termo = $_GET['q'] ?? '';

// Certifique-se de que tipo não está vazio para evitar comportamento inesperado
if (empty($tipo)) {
    echo json_encode(['results' => []]);
    exit;
}

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