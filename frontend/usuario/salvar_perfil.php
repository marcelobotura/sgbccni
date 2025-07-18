<?php
define('BASE_PATH', realpath(__DIR__ . '/../backend'));
require_once BASE_PATH . '/config/config.php';
require_once BASE_PATH . '/includes/session.php';

exigir_login('usuario');

$id = $_SESSION['usuario_id'];
$nome = trim($_POST['nome'] ?? '');
$email = trim($_POST['email'] ?? '');
$nova_senha = $_POST['nova_senha'] ?? '';
$foto_atual = $_SESSION['usuario_foto'] ?? null;

if (empty($nome) || empty($email)) {
    $_SESSION['erro'] = "Nome e e-mail são obrigatórios.";
    header("Location: perfil.php");
    exit;
}

// Verifica se o e-mail já está em uso por outro usuário
$stmt = $conn->prepare("SELECT id FROM usuarios WHERE email = ? AND id != ?");
$stmt->bind_param("si", $email, $id);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {
    $_SESSION['erro'] = "Este e-mail já está sendo usado por outro usuário.";
    header("Location: perfil.php");
    exit;
}
$stmt->close();

// Inicializa SQL
$senha_sql = "";
$foto_sql = "";
$params = [$nome, $email];
$types = "ss";

// Atualiza senha, se fornecida
if (!empty($nova_senha)) {
    $senha_hash = password_hash($nova_senha, PASSWORD_DEFAULT);
    $senha_sql = ", senha = ?";
    $params[] = $senha_hash;
    $types .= "s";
}

// Upload da nova foto
if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
    $extensao = strtolower(pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION));
    $novo_nome = uniqid('perfil_', true) . '.' . $extensao;
    $destino = BASE_PATH . '/../uploads/perfis/' . $novo_nome;

    if (move_uploaded_file($_FILES['foto']['tmp_name'], $destino)) {
        // Exclui foto antiga
        if ($foto_atual && file_exists(BASE_PATH . '/../uploads/perfis/' . $foto_atual)) {
            unlink(BASE_PATH . '/../uploads/perfis/' . $foto_atual);
        }

        $foto_sql = ", foto = ?";
        $params[] = $novo_nome;
        $types .= "s";
        $_SESSION['usuario_foto'] = $novo_nome;
    } else {
        $_SESSION['erro'] = "Erro ao enviar a nova foto de perfil.";
        header("Location: perfil.php");
        exit;
    }
}

// Monta e executa o UPDATE
$sql = "UPDATE usuarios SET nome = ?, email = ?{$senha_sql}{$foto_sql} WHERE id = ?";
$params[] = $id;
$types .= "i";

$stmt = $conn->prepare($sql);
$stmt->bind_param($types, ...$params);

if ($stmt->execute()) {
    $_SESSION['usuario_nome'] = $nome;
    $_SESSION['usuario_email'] = $email;
    $_SESSION['sucesso'] = "✅ Perfil atualizado com sucesso!";
} else {
    $_SESSION['erro'] = "Erro ao atualizar perfil: " . $conn->error;
}

$stmt->close();
header("Location: perfil.php");
exit;
