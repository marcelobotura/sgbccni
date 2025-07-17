<?php
// Define que o retorno será em JSON
header('Content-Type: application/json');

// ✅ Só permite requisição POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode([
        'status' => 'erro',
        'mensagem' => 'Método não permitido. Use POST.'
    ]);
    exit;
}

// ✅ Carrega configurações do banco
require_once __DIR__ . '/../../../backend/config/config.php';

// ✅ Recebe e valida os dados do POST
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
    // 🔍 Verifica se já existe tag com o mesmo nome e tipo
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

    // ✅ Insere a nova tag
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
