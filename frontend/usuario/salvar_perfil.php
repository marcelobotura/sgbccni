<?php
// Caminho: frontend/usuario/salvar_perfil.php

define('BASE_PATH', dirname(__DIR__, 2) . '/backend');
require_once BASE_PATH . '/config/config.php';
require_once BASE_PATH . '/includes/session.php';
require_once __DIR__ . '/protect_usuario.php';

exigir_login('usuario');

$id = $_SESSION['usuario_id'];
$nome = trim($_POST['nome'] ?? '');
$email = trim($_POST['email'] ?? '');
$nova_senha = $_POST['nova_senha'] ?? '';
$foto_atual = $_SESSION['usuario_foto'] ?? null;

if (empty($nome) || empty($email)) {
    $_SESSION['erro'] = "Nome e e-mail são obrigatórios.";
    header("Location: editar_perfil.php");
    exit;
}

// 🧪 Verifica se e-mail já está em uso por outro
$stmt = $pdo->prepare("SELECT id FROM usuarios WHERE email = ? AND id != ?");
$stmt->execute([$email, $id]);
if ($stmt->rowCount() > 0) {
    $_SESSION['erro'] = "Este e-mail já está sendo usado por outro usuário.";
    header("Location: editar_perfil.php");
    exit;
}

// 📸 Upload da imagem, se existir
$nova_foto = $foto_atual;
if (!empty($_FILES['foto']['name'])) {
    $permitidos = ['image/jpeg', 'image/png'];
    $tipo = $_FILES['foto']['type'];
    $tamanho = $_FILES['foto']['size'];
    $tmp = $_FILES['foto']['tmp_name'];

    if (!in_array($tipo, $permitidos)) {
        $_SESSION['erro'] = "Formato inválido. Envie JPG ou PNG.";
        header("Location: editar_perfil.php");
        exit;
    }

    if ($tamanho > 2 * 1024 * 1024) {
        $_SESSION['erro'] = "Imagem muito grande. Limite de 2MB.";
        header("Location: editar_perfil.php");
        exit;
    }

    $extensao = pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION);
    $nome_arquivo = 'perfil_' . $id . '_' . time() . '.' . $extensao;
   $destino = dirname(__DIR__, 2) . '/storage/uploads/perfis/' . $nome_arquivo;


    if (move_uploaded_file($tmp, $destino)) {
        $nova_foto = $nome_arquivo;

        // Remove a foto anterior se não for a padrão
        if (!empty($foto_atual) && file_exists(dirname(__DIR__, 2) . '/uploads/perfis/' . $foto_atual)) {
            @unlink(dirname(__DIR__, 2) . '/uploads/perfis/' . $foto_atual);
        }
    } else {
        $_SESSION['erro'] = "Erro ao fazer upload da nova imagem de perfil.";
        header("Location: editar_perfil.php");
        exit;
    }
}

// 🔐 Atualiza os dados
$sql = "UPDATE usuarios SET nome = ?, email = ?, foto = ?" . (!empty($nova_senha) ? ", senha = ?" : "") . " WHERE id = ?";
$params = [$nome, $email, $nova_foto];
if (!empty($nova_senha)) {
    $senha_hash = password_hash($nova_senha, PASSWORD_DEFAULT);
    $params[] = $senha_hash;
}
$params[] = $id;

$stmt = $pdo->prepare($sql);
$stmt->execute($params);

// 🧠 Atualiza sessão
$_SESSION['usuario_nome'] = $nome;
$_SESSION['usuario_email'] = $email;
$_SESSION['usuario_foto'] = $nova_foto;

$_SESSION['sucesso'] = "Perfil atualizado com sucesso!";
header("Location: ver_perfil.php");
exit;
