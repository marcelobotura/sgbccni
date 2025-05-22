<?php
require_once __DIR__ . '/../../../config/config.php';
require_once __DIR__ . '/../../../includes/session.php';

// 🔐 Garante que o usuário esteja logado
exigir_login('usuario');

// 🔄 Coleta e sanitiza os dados
$id             = $_SESSION['usuario_id'];
$nome           = trim(filter_input(INPUT_POST, 'nome', FILTER_SANITIZE_STRING));
$data_nascimento= $_POST['data_nascimento'] ?? null;
$genero         = $_POST['genero'] ?? null;
$cep            = trim($_POST['cep'] ?? '');
$endereco       = trim($_POST['endereco'] ?? '');
$cidade         = trim($_POST['cidade'] ?? '');
$estado         = trim($_POST['estado'] ?? '');

// Validação mínima
if (!$id || !$nome) {
    $_SESSION['erro'] = "Nome é obrigatório.";
    header("Location: " . URL_BASE . "usuario/perfil.php");
    exit;
}

// 📸 Upload de imagem
$imagem_perfil = $_SESSION['usuario_foto'] ?? '';
if (!empty($_FILES['foto_perfil']['name'])) {
    $permitidos = ['image/jpeg', 'image/png', 'image/webp'];
    $tipo = mime_content_type($_FILES['foto_perfil']['tmp_name']);

    if (!in_array($tipo, $permitidos)) {
        $_SESSION['erro'] = "Formato de imagem inválido.";
        header("Location: " . URL_BASE . "usuario/perfil.php");
        exit;
    }

    $ext = pathinfo($_FILES['foto_perfil']['name'], PATHINFO_EXTENSION);
    $novo_nome = uniqid('perfil_', true) . '.' . $ext;
    $destino = __DIR__ . '/../../../uploads/perfis/' . $novo_nome;

    if (move_uploaded_file($_FILES['foto_perfil']['tmp_name'], $destino)) {
        $imagem_perfil = 'uploads/perfis/' . $novo_nome;
        $_SESSION['usuario_foto'] = $imagem_perfil;
    } else {
        $_SESSION['erro'] = "Erro ao salvar a imagem.";
        header("Location: " . URL_BASE . "usuario/perfil.php");
        exit;
    }
}

// 💾 Atualiza os dados no banco
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
    $_SESSION['usuario_nome'] = htmlspecialchars($nome);
    $_SESSION['sucesso'] = "Perfil atualizado com sucesso!";
} else {
    $_SESSION['erro'] = "Erro ao atualizar perfil: " . $stmt->error;
}

header("Location: " . URL_BASE . "usuario/perfil.php");
exit;
