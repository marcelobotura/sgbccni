<?php
require_once '../../includes/config.php';

// Força o retorno como JSON
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = trim($_POST['nome'] ?? '');
    $tipo = trim($_POST['tipo'] ?? '');

    if ($nome === '' || !in_array($tipo, ['autor', 'categoria', 'editora', 'outro'])) {
        echo json_encode(['status' => 'erro', 'mensagem' => 'Dados inválidos']);
        exit;
    }

    // Verifica se a tag já existe
    $stmt = $conn->prepare("SELECT id FROM tags WHERE nome = ? AND tipo = ?");
    $stmt->bind_param("ss", $nome, $tipo);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows === 0) {
        $stmt->close();

        $stmtInsert = $conn->prepare("INSERT INTO tags (nome, tipo) VALUES (?, ?)");
        $stmtInsert->bind_param("ss", $nome, $tipo);

        if ($stmtInsert->execute()) {
            echo json_encode(['status' => 'ok', 'mensagem' => '✅ Tag adicionada com sucesso.']);
        } else {
            echo json_encode(['status' => 'erro', 'mensagem' => 'Erro ao salvar tag.']);
        }

        $stmtInsert->close();
    } else {
        echo json_encode(['status' => 'existe', 'mensagem' => '⚠️ Tag já cadastrada.']);
        $stmt->close();
    }
} else {
    echo json_encode(['status' => 'erro', 'mensagem' => 'Requisição inválida']);
}
