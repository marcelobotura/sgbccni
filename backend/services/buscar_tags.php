<?php
header('Content-Type: application/json');

require_once __DIR__ . '/../config/config.php';

$tipo  = trim($_GET['tipo'] ?? '');
$termo = trim($_GET['q'] ?? '');

if (empty($tipo)) {
    echo json_encode([]);
    exit;
}

try {
    // 🔍 Consulta as tags existentes
    $stmt = $conn->prepare("
        SELECT id, nome 
        FROM tags 
        WHERE tipo = :tipo AND nome LIKE :termo 
        ORDER BY nome ASC 
        LIMIT 10
    ");
    $stmt->execute([
        ':tipo'  => $tipo,
        ':termo' => "%$termo%"
    ]);

    $dados = [];
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $dados[] = [
            'id'   => $row['id'],
            'text' => $row['nome']
        ];
    }

    // 🔥 Se não existir ainda, sugere a criação
    $nomes_existentes = array_column($dados, 'text');
    if ($termo !== '' && !in_array($termo, $nomes_existentes)) {
        $dados[] = [
            'id'        => $termo,
            'text'      => "➕ Criar nova: $termo",
            'newOption' => true
        ];
    }

    echo json_encode([
        'results' => $dados
    ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

} catch (PDOException $e) {
    echo json_encode([
        'erro' => 'Erro na busca: ' . $e->getMessage()
    ]);
}
?>
