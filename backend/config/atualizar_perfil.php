<?php
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../includes/session.php';

// 🔒 Protege contra acesso não autorizado
exigir_login('usuario');

// 🔄 Dados do formulário com validação e sanitização
$id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
$nome = trim(filter_input(INPUT_POST, 'nome', FILTER_SANITIZE_STRING));
$data_nascimento = $_POST['data_nascimento'] ?? null;
$genero = $_POST['genero'] ?? null;
$cep = $_POST['cep'] ?? null;
$endereco = trim(filter_input(INPUT_POST, 'endereco', FILTER_SANITIZE_SPECIAL_CHARS));
$cidade = trim(filter_input(INPUT_POST, 'cidade', FILTER_SANITIZE_SPECIAL_CHARS));
$estado = trim(filter_input(INPUT_POST, 'estado', FILTER_SANITIZE_SPECIAL_CHARS));

// ✅ Validação mínima
if (!$id || !$nome) {
    $_SESSION['erro'] = "Preencha os campos obrigatórios.";
    header("Location: " . URL_BASE . "usuario/perfil.php");
    exit;
}

// 📸 Upload de imagem com validação de tipo
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
    $destino = __DIR__ . '/../../uploads/perfis/' . $novo_nome;

    // Exclui a imagem anterior, se existir
    if (!empty($imagem_perfil) && file_exists(__DIR__ . '/../../' . $imagem_perfil)) {
        unlink(__DIR__ . '/../../' . $imagem_perfil);
    }

    if (move_uploaded_file($_FILES['foto_perfil']['tmp_name'], $destino)) {
        $imagem_perfil = 'uploads/perfis/' . $novo_nome;
        $_SESSION['usuario_foto'] = $imagem_perfil;
    } else {
        $_SESSION['erro'] = "Erro ao salvar a imagem.";
        header("Location: " . URL_BASE . "usuario/perfil.php");
        exit;
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
    $_SESSION['usuario_nome'] = htmlspecialchars($nome); // segurança para exibição
    $_SESSION['sucesso'] = "Perfil atualizado com sucesso!";
} else {
    $_SESSION['erro'] = "Erro ao atualizar perfil. Tente novamente.";
    error_log("Erro MySQL: " . $stmt->error);
}

header("Location: " . URL_BASE . "usuario/perfil.php");
exit;
