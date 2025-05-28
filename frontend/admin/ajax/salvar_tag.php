<?php
require_once __DIR__ . '/../../../backend/config/config.php';
header('Content-Type: application/json');

// ✅ Verifica método
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405); // Método não permitido
    echo json_encode([
        'status' => 'erro',
        'mensagem' => 'Método não permitido.'
    ]);
    exit;
}

// ✅ Recebe e valida dados
$nome = trim($_POST['nome'] ?? '');
$tipo = trim($_POST['tipo'] ?? '');

$tipos_validos = ['autor', 'categoria', 'editora', 'outro'];
if ($nome === '' || !in_array($tipo, $tipos_validos)) {
    http_response_code(400);
    echo json_encode([
        'status' => 'erro',
        'mensagem' => 'Dados inválidos.'
    ]);
    exit;
}

// 🔍 Verifica se já existe
$stmt = $conn->prepare("SELECT id FROM tags WHERE nome = ? AND tipo = ?");
$stmt->bind_param("ss", $nome, $tipo);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {
    $stmt->bind_result($id_existente);
    $stmt->fetch();
    $stmt->close();

    echo json_encode([
        'status'   => 'existe',
        'mensagem' => '⚠️ Tag já cadastrada.',
        'id'       => $id_existente,
        'text'     => $nome
    ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    exit;
}
$stmt->close();

// ✅ Insere nova tag
$stmtInsert = $conn->prepare("INSERT INTO tags (nome, tipo) VALUES (?, ?)");
$stmtInsert->bind_param("ss", $nome, $tipo);

if ($stmtInsert->execute()) {
    echo json_encode([
        'status'   => 'ok',
        'mensagem' => '✅ Tag adicionada com sucesso.',
        'id'       => $stmtInsert->insert_id,
        'text'     => $nome
    ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
} else {
    http_response_code(500);
    echo json_encode([
        'status' => 'erro',
        'mensagem' => 'Erro ao salvar tag.'
    ]);
}
$stmtInsert->close();
