<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../includes/session.php';

// 🔐 Apenas administradores podem usar
exigir_login('admin');

// 🔄 Coleta dados do POST
$id              = $_POST['id'] ?? null;
$nome            = trim(filter_input(INPUT_POST, 'nome', FILTER_SANITIZE_STRING));
$email           = trim(filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL));
$data_nascimento = $_POST['data_nascimento'] ?? null;
$genero          = $_POST['genero'] ?? null;
$cep             = trim($_POST['cep'] ?? '');
$endereco        = trim($_POST['endereco'] ?? '');
$cidade          = trim($_POST['cidade'] ?? '');
$estado          = trim($_POST['estado'] ?? '');
$tipo            = $_POST['tipo'] ?? 'usuario';

// ✅ Validação mínima
if (!$id || !$nome || !$email) {
    $_SESSION['erro'] = "Nome, e-mail e ID são obrigatórios.";
    header("Location: " . URL_BASE . "admin/pages/editar_usuario.php?id=$id");
    exit;
}

// 📸 Upload de imagem (se houver)
$imagem_perfil = $_POST['imagem_atual'] ?? ''; // Valor anterior
if (!empty($_FILES['foto_perfil']['name'])) {
    $permitidos = ['image/jpeg', 'image/png', 'image/webp'];
    $tipo = mime_content_type($_FILES['foto_perfil']['tmp_name']);

    if (!in_array($tipo, $permitidos)) {
        $_SESSION['erro'] = "Formato de imagem inválido.";
        header("Location: " . URL_BASE . "admin/pages/editar_usuario.php?id=$id");
        exit;
    }

    $ext = pathinfo($_FILES['foto_perfil']['name'], PATHINFO_EXTENSION);
    $novo_nome = uniqid('perfil_', true) . '.' . $ext;
    $destino = __DIR__ . '/../uploads/perfis/' . $novo_nome;

    if (move_uploaded_file($_FILES['foto_perfil']['tmp_name'], $destino)) {
        $imagem_perfil = 'uploads/perfis/' . $novo_nome;
    } else {
        $_SESSION['erro'] = "Erro ao salvar a imagem.";
        header("Location: " . URL_BASE . "admin/pages/editar_usuario.php?id=$id");
        exit;
    }
}

// 💾 Atualiza no banco
$stmt = $conn->prepare("UPDATE usuarios SET 
    nome = ?, email = ?, imagem_perfil = ?, data_nascimento = ?, genero = ?, 
    cep = ?, endereco = ?, cidade = ?, estado = ?, tipo = ? 
    WHERE id = ?");

$stmt->bind_param(
    "ssssssssssi",
    $nome, $email, $imagem_perfil, $data_nascimento, $genero,
    $cep, $endereco, $cidade, $estado, $tipo, $id
);

if ($stmt->execute()) {
    $_SESSION['sucesso'] = "Usuário atualizado com sucesso!";
} else {
    $_SESSION['erro'] = "Erro ao atualizar: " . $stmt->error;
}

header("Location: " . URL_BASE . "admin/pages/editar_usuario.php?id=$id");
exit;
