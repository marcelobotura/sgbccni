<?php
// Caminho: frontend/usuario/salvar_perfil.php

define('BASE_PATH', dirname(__DIR__, 2) . '/backend');
require_once BASE_PATH . '/config/config.php';
require_once BASE_PATH . '/includes/session.php';

exigir_login('usuario');

// Dados do formulário
$id         = $_SESSION['usuario_id'];
$nome       = trim($_POST['nome'] ?? '');
$email      = trim($_POST['email'] ?? '');
$nova_senha = $_POST['nova_senha'] ?? '';
$foto_atual = $_SESSION['usuario_foto'] ?? null;

// Validação básica
if (empty($nome) || empty($email)) {
    $_SESSION['erro'] = "Nome e e-mail são obrigatórios.";
    header("Location: editar_perfil.php");
    exit;
}

// Verifica se e-mail já está em uso por outro usuário
$stmt = $pdo->prepare("SELECT id FROM usuarios WHERE email = ? AND id != ?");
$stmt->execute([$email, $id]);
if ($stmt->rowCount() > 0) {
    $_SESSION['erro'] = "Este e-mail já está sendo usado por outro usuário.";
    header("Location: editar_perfil.php");
    exit;
}

// Hash da senha, se fornecida
$senha_hash = null;
if (!empty($nova_senha)) {
    $senha_hash = password_hash($nova_senha, PASSWORD_DEFAULT);
}

// Upload da imagem de perfil, se enviada
$nova_foto = null;
$caminho_pasta = dirname(__DIR__, 2) . '/storage/uploads/perfis/';

if (!empty($_FILES['foto']['name']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
    $extensao = strtolower(pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION));
    $nome_arquivo = 'perfil_' . $id . '_' . time() . '.' . $extensao;
    $destino = $caminho_pasta . $nome_arquivo;

    // Cria a pasta se não existir
    if (!is_dir($caminho_pasta)) {
        mkdir($caminho_pasta, 0755, true);
    }

    // Move o arquivo
    if (move_uploaded_file($_FILES['foto']['tmp_name'], $destino)) {
        $nova_foto = $nome_arquivo;

        // Remove a imagem anterior se existir
        $caminho_antigo = $caminho_pasta . $foto_atual;
        if (!empty($foto_atual) && file_exists($caminho_antigo)) {
            unlink($caminho_antigo);
        }
    } else {
        $_SESSION['erro'] = "Erro ao fazer upload da imagem.";
        header("Location: editar_perfil.php");
        exit;
    }
}

// Define a imagem final a ser salva
$foto_final = $nova_foto ?? $foto_atual;

// Atualiza os dados no banco
try {
    if ($senha_hash) {
        $stmt = $pdo->prepare("UPDATE usuarios SET nome = ?, email = ?, senha = ?, foto = ? WHERE id = ?");
        $stmt->execute([$nome, $email, $senha_hash, $foto_final, $id]);
    } else {
        $stmt = $pdo->prepare("UPDATE usuarios SET nome = ?, email = ?, foto = ? WHERE id = ?");
        $stmt->execute([$nome, $email, $foto_final, $id]);
    }

    // Atualiza a sessão com os novos dados
    $_SESSION['usuario_nome']  = $nome;
    $_SESSION['usuario_email'] = $email;
    $_SESSION['usuario_foto']  = $foto_final;

    $_SESSION['sucesso'] = "Perfil atualizado com sucesso!";
} catch (PDOException $e) {
    $_SESSION['erro'] = "Erro ao atualizar o perfil: " . $e->getMessage();
}

header("Location: editar_perfil.php");
exit;
