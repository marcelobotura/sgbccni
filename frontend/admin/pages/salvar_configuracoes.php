<?php
session_start();
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../includes/session.php';
require_once __DIR__ . '/../../includes/verifica_admin.php';
require_once __DIR__ . '/../../includes/protect_admin.php';

exigir_login('admin');

// 🔍 Lista de chaves válidas que podem ser salvas
$chaves = [
    'nome_sistema',
    'email',
    'telefone',
    'endereco',
    'descricao',
    'url'
];

try {
    $conn->beginTransaction();

    foreach ($chaves as $chave) {
        $valor = trim($_POST[$chave] ?? '');

        // Verifica se a configuração já existe
        $stmt = $conn->prepare("SELECT id FROM configuracoes WHERE chave = :chave");
        $stmt->execute([':chave' => $chave]);

        if ($stmt->rowCount() > 0) {
            // Atualiza se já existe
            $update = $conn->prepare("UPDATE configuracoes SET valor = :valor WHERE chave = :chave");
            $update->execute([
                ':valor' => $valor,
                ':chave' => $chave
            ]);
        } else {
            // Insere se não existir
            $insert = $conn->prepare("INSERT INTO configuracoes (chave, valor) VALUES (:chave, :valor)");
            $insert->execute([
                ':chave' => $chave,
                ':valor' => $valor
            ]);
        }
    }

    $conn->commit();
    $_SESSION['sucesso'] = "✅ Configurações atualizadas com sucesso.";
} catch (PDOException $e) {
    $conn->rollBack();
    $_SESSION['erro'] = "❌ Erro ao salvar configurações: " . $e->getMessage();
}

// 🔄 Redireciona de volta
header('Location: ../../../frontend/admin/pages/configuracoes.php');
exit;
?>
