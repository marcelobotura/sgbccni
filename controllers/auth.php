<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../config/config.php';

$acao = $_POST['acao'] ?? $_GET['acao'] ?? '';

// --- REGISTRO DE USUÁRIO ---
if ($acao === 'register') {
    $nome = trim($_POST['nome'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $senha = $_POST['senha'] ?? '';
    $tipo = $_POST['tipo'] ?? 'usuario';

    $data_nascimento = $_POST['data_nascimento'] ?? null;
    $genero = $_POST['genero'] ?? null;
    $cep = $_POST['cep'] ?? null;
    $endereco = $_POST['endereco'] ?? null;
    $cidade = $_POST['cidade'] ?? null;
    $estado = $_POST['estado'] ?? null;

    // Foto de perfil (opcional)
    $imagem_perfil = '';
    if (!empty($_FILES['foto_perfil']['name'])) {
        $ext = pathinfo($_FILES['foto_perfil']['name'], PATHINFO_EXTENSION);
        $foto_nome = uniqid() . '.' . $ext;
        $destino = __DIR__ . '/../uploads/perfis/' . $foto_nome;
        if (move_uploaded_file($_FILES['foto_perfil']['tmp_name'], $destino)) {
            $imagem_perfil = 'uploads/perfis/' . $foto_nome;
        }
    }

    $senha_hash = password_hash($senha, PASSWORD_DEFAULT);

    $stmt = $conn->prepare("INSERT INTO usuarios (
        nome, email, senha, tipo, imagem_perfil,
        data_nascimento, genero, cep, endereco, cidade, estado, ativo
    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 1)");

    $stmt->bind_param(
        "sssssssssss",
        $nome, $email, $senha_hash, $tipo, $imagem_perfil,
        $data_nascimento, $genero, $cep, $endereco, $cidade, $estado
    );

    if ($stmt->execute()) {
        $_SESSION['sucesso'] = "Cadastro realizado com sucesso!";
        header("Location: ../login/");
    } else {
        $_SESSION['erro'] = "Erro ao cadastrar: " . $stmt->error;
        header("Location: ../login/register.php");
    }
    exit;
}

// --- LOGIN DE USUÁRIO ---
if ($acao === 'login') {
    $email = trim($_POST['usuario'] ?? '');
    $senha = $_POST['senha'] ?? '';

    $stmt = $conn->prepare("SELECT id, nome, senha, tipo, imagem_perfil FROM usuarios WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows === 1) {
        $stmt->bind_result($id, $nome, $senha_hash, $tipo, $foto);
        $stmt->fetch();

        if (password_verify($senha, $senha_hash)) {
            $_SESSION['usuario_id'] = $id;
            $_SESSION['usuario_nome'] = $nome;
            $_SESSION['usuario_tipo'] = $tipo;
            $_SESSION['usuario_foto'] = $foto;

            // ✅ Corrigido para redirecionar ao painel certo
            $destino = ($tipo === 'admin') ? "../admin/" : "../usuario/";
            header("Location: $destino");
        } else {
            $_SESSION['erro'] = "Senha incorreta.";
            header("Location: ../login/");
        }
    } else {
        $_SESSION['erro'] = "Usuário não encontrado.";
        header("Location: ../login/");
    }
    exit;
}

// --- LOGOUT DE USUÁRIO ---
if ($acao === 'logout') {
    session_destroy();
    header("Location: ../login/");
    exit;
}
