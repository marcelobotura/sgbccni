<?php
require_once __DIR__ . '/../../../backend/config/config.php';
header('Content-Type: application/json');

// ✅ Verifica se o método é POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode([
        'status' => 'erro',
        'mensagem' => 'Método não permitido. Use POST.'
    ]);
    exit;
}

// ✅ Recebe e valida os dados
$nome = trim($_POST['nome'] ?? '');
$tipo = trim($_POST['tipo'] ?? '');

$tipos_validos = ['autor', 'categoria', 'editora', 'outro'];

if ($nome === '' || !in_array($tipo, $tipos_validos)) {
    http_response_code(400);
    echo json_encode([
        'status' => 'erro',
        'mensagem' => 'Dados inválidos. Informe nome e tipo válido.'
    ]);
    exit;
}

try {
    // 🔍 Verifica se a tag já existe
    $stmt = $conn->prepare("SELECT id FROM tags WHERE nome = ? AND tipo = ?");
    $stmt->execute([$nome, $tipo]);
    $tagExistente = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($tagExistente) {
        echo json_encode([
            'status'   => 'existe',
            'mensagem' => '⚠️ Tag já cadastrada.',
            'id'       => $tagExistente['id'],
            'text'     => $nome
        ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        exit;
    }

    // ✅ Insere nova tag
    $stmtInsert = $conn->prepare("INSERT INTO tags (nome, tipo) VALUES (?, ?)");
    $stmtInsert->execute([$nome, $tipo]);

    echo json_encode([
        'status'   => 'ok',
        'mensagem' => '✅ Tag adicionada com sucesso.',
        'id'       => $conn->lastInsertId(),
        'text'     => $nome
    ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode([
        'status'   => 'erro',
        'mensagem' => 'Erro no servidor: ' . $e->getMessage()
    ]);
}
?>
