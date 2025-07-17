<?php
require_once __DIR__ . '/../../../config/config.php';
require_once __DIR__ . '/../../../includes/session.php';
exigir_login('admin');

// 🧪 Validação
$id              = intval($_POST['id'] ?? 0);
$titulo          = trim($_POST['titulo'] ?? '');
$isbn            = trim($_POST['isbn'] ?? '');
$volume          = trim($_POST['volume'] ?? '');
$edicao          = trim($_POST['edicao'] ?? '');
$codigo_interno  = trim($_POST['codigo_interno'] ?? '');
$descricao       = trim($_POST['descricao'] ?? '');
$tipo            = $_POST['tipo'] ?? 'físico';
$formato         = $_POST['formato'] ?? '';
$link_digital    = trim($_POST['link_digital'] ?? '');
$autor_id        = intval($_POST['autor_id'] ?? 0);
$editora_id      = intval($_POST['editora_id'] ?? 0);
$categoria_id    = intval($_POST['categoria_id'] ?? 0);

if ($id <= 0 || $titulo === '' || $isbn === '' || $codigo_interno === '') {
    $_SESSION['erro'] = "Preencha todos os campos obrigatórios.";
    header("Location: ../../../frontend/admin/pages/editar_livro.php?id=$id");
    exit;
}

// 📁 Upload de nova capa (opcional)
$capa_nome = null;
if (!empty($_FILES['nova_capa']['name'])) {
    $extensao = pathinfo($_FILES['nova_capa']['name'], PATHINFO_EXTENSION);
    $capa_nome = uniqid('capa_') . '.' . strtolower($extensao);
    $caminho_destino = __DIR__ . '/../../../uploads/capas/' . $capa_nome;

    if (!move_uploaded_file($_FILES['nova_capa']['tmp_name'], $caminho_destino)) {
        $_SESSION['erro'] = "Erro ao fazer upload da nova capa.";
        header("Location: ../../../frontend/admin/pages/editar_livro.php?id=$id");
        exit;
    }
}

try {
    $sql = "UPDATE livros SET 
                titulo = :titulo,
                isbn = :isbn,
                volume = :volume,
                edicao = :edicao,
                codigo_interno = :codigo_interno,
                descricao = :descricao,
                tipo = :tipo,
                formato = :formato,
                link_digital = :link_digital,
                autor_id = :autor_id,
                editora_id = :editora_id,
                categoria_id = :categoria_id";

    if ($capa_nome) {
        $sql .= ", capa = :capa";
    }

    $sql .= " WHERE id = :id";

    $stmt = $conn->prepare($sql);
    $stmt->bindValue(':titulo', $titulo);
    $stmt->bindValue(':isbn', $isbn);
    $stmt->bindValue(':volume', $volume);
    $stmt->bindValue(':edicao', $edicao);
    $stmt->bindValue(':codigo_interno', $codigo_interno);
    $stmt->bindValue(':descricao', $descricao);
    $stmt->bindValue(':tipo', $tipo);
    $stmt->bindValue(':formato', $formato);
    $stmt->bindValue(':link_digital', $link_digital);
    $stmt->bindValue(':autor_id', $autor_id);
    $stmt->bindValue(':editora_id', $editora_id);
    $stmt->bindValue(':categoria_id', $categoria_id);
    if ($capa_nome) {
        $stmt->bindValue(':capa', $capa_nome);
    }
    $stmt->bindValue(':id', $id, PDO::PARAM_INT);

    $stmt->execute();

    $_SESSION['sucesso'] = "Livro atualizado com sucesso!";
    header("Location: ../../../frontend/admin/pages/listar_livros.php");
    exit;
} catch (PDOException $e) {
    $_SESSION['erro'] = "Erro ao atualizar livro: " . $e->getMessage();
    header("Location: ../../../frontend/admin/pages/editar_livro.php?id=$id");
    exit;
}
