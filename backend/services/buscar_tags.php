<?php
header('Content-Type: application/json');

require_once __DIR__ . '/../config/config.php';

$tipo  = trim($_GET['tipo'] ?? '');
$termo = trim($_GET['q'] ?? '');

if (empty($tipo)) {
    echo json_encode([]);
    exit;
}

$termo_completo = "%$termo%";

$stmt = $conn->prepare("
    SELECT id, nome 
    FROM tags 
    WHERE tipo = ? AND nome LIKE ? 
    ORDER BY nome ASC 
    LIMIT 10
");
$stmt->bind_param("ss", $tipo, $termo_completo);
$stmt->execute();
$result = $stmt->get_result();

$dados = [];
while ($row = $result->fetch_assoc()) {
    $dados[] = [
        'id'   => $row['id'],
        'text' => $row['nome']
    ];
}

$nomes_existentes = array_column($dados, 'text');
if ($termo !== '' && !in_array($termo, $nomes_existentes)) {
    $dados[] = [
        'id' => $termo,
        'text' => "➕ Criar nova: $termo",
        'newOption' => true
    ];
}

echo json_encode($dados, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
