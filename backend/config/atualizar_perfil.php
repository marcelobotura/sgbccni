<?php
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../includes/session.php';

// 🔒 Protege contra acesso não autorizado
exigir_login('usuario');

// 🔄 Dados do formulário
$id = $_POST['id'] ?? null;
$nome = trim($_POST['nome'] ?? '');
$data_nascimento = $_POST['data_nascimento'] ?? null;
$genero = $_POST['genero'] ?? null;
$cep = $_POST['cep'] ?? null;
$endereco = $_POST['endereco'] ?? null;
$cidade = $_POST['cidade'] ?? null;
$estado = $_POST['estado'] ?? null;

// Validação básica
if (!$id || !$nome) {
    $_SESSION['erro'] = "Preencha os campos obrigatórios.";
    header("Location: " . URL_BASE . "usuario/perfil.php");
    exit;
}

// 📸 Upload de imagem (se houver)
$imagem_perfil = $_SESSION['usuario_foto'] ?? '';
if (!empty($_FILES['foto_perfil']['name'])) {
    $ext = pathinfo($_FILES['foto_perfil']['name'], PATHINFO_EXTENSION);
    $novo_nome = uniqid('perfil_', true) . '.' . $ext;
    $destino = __DIR__ . '/../../uploads/perfis/' . $novo_nome;

    if (move_uploaded_file($_FILES['foto_perfil']['tmp_name'], $destino)) {
        $imagem_perfil = 'uploads/perfis/' . $novo_nome;
        $_SESSION['usuario_foto'] = $imagem_perfil;
    }
}

// 💾 Atualiza no banco
$stmt = $conn->prepare("UPDATE usuarios SET 
    nome = ?, imagem_perfil = ?, data_nascimento = ?, genero = ?, 
    cep = ?, endereco = ?, cidade = ?, estado = ? 
    WHERE id = ?");

$stmt->bind_param(
    "ssssssssi",
    $nome, $imagem_perfil, $data_nascimento, $genero,
    $cep, $endereco, $cidade, $estado, $id
);

if ($stmt->execute()) {
    $_SESSION['usuario_nome'] = $nome;
    $_SESSION['sucesso'] = "Perfil atualizado com sucesso!";
} else {
    $_SESSION['erro'] = "Erro ao atualizar perfil: " . $stmt->error;
}

header("Location: " . URL_BASE . "usuario/perfil.php");
exit;
