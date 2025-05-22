<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../includes/session.php';
require_once __DIR__ . '/auth.php';

header('Content-Type: application/json');

// 🔐 Apenas admin pode salvar tag
exigir_login('admin');

// 🧼 Coleta e limpa dados
$nome = trim($_POST['nome'] ?? '');
$tipo = $_POST['tipo'] ?? '';

if (empty($nome) || empty($tipo)) {
    echo json_encode(['status' => 'erro', 'mensagem' => 'Campos obrigatórios ausentes.']);
    exit;
}

// Verifica se já existe
$stmt = $conn->prepare("SELECT id FROM tags WHERE nome = ? AND tipo = ?");
$stmt->bind_param("ss", $nome, $tipo);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {
    echo json_encode(['status' => 'existe', 'mensagem' => 'Tag já cadastrada.']);
    exit;
}
$stmt->close();

// Insere nova tag
$stmt = $conn->prepare("INSERT INTO tags (nome, tipo) VALUES (?, ?)");
$stmt->bind_param("ss", $nome, $tipo);

if ($stmt->execute()) {
    echo json_encode([
        'status' => 'sucesso',
        'mensagem' => 'Tag cadastrada com sucesso!',
        'id' => $stmt->insert_id,
        'nome' => $nome,
        'tipo' => $tipo
    ]);
} else {
    echo json_encode(['status' => 'erro', 'mensagem' => 'Erro ao salvar: ' . $stmt->error]);
}
