<?php
require_once __DIR__ . '/../../../backend/config/config.php';
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['status' => 'erro', 'mensagem' => 'Requisição inválida']);
    exit;
}

$nome = trim($_POST['nome'] ?? '');
$tipo = trim($_POST['tipo'] ?? '');

if ($nome === '' || !in_array($tipo, ['autor', 'categoria', 'editora', 'outro'])) {
    echo json_encode(['status' => 'erro', 'mensagem' => 'Dados inválidos']);
    exit;
}

// Verifica duplicidade
$stmt = $conn->prepare("SELECT id FROM tags WHERE nome = ? AND tipo = ?");
$stmt->bind_param("ss", $nome, $tipo);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows === 0) {
    $stmt->close();

    $stmtInsert = $conn->prepare("INSERT INTO tags (nome, tipo) VALUES (?, ?)");
    $stmtInsert->bind_param("ss", $nome, $tipo);

    if ($stmtInsert->execute()) {
        echo json_encode([
            'status'   => 'ok',
            'mensagem' => '✅ Tag adicionada com sucesso.',
            'id'       => $stmtInsert->insert_id,
            'text'     => $nome
        ], JSON_UNESCAPED_UNICODE);
    } else {
        echo json_encode(['status' => 'erro', 'mensagem' => 'Erro ao salvar tag.']);
    }

    $stmtInsert->close();
} else {
    $stmt->bind_result($id_existente);
    $stmt->fetch();
    echo json_encode([
        'status' => 'existe',
        'mensagem' => '⚠️ Tag já cadastrada.',
        'id' => $id_existente,
        'text' => $nome
    ]);
    $stmt->close();
}
