<?php
require_once __DIR__ . '/../../../config/config.php';
require_once __DIR__ . '/../../../includes/session.php';
require_once __DIR__ . '/../../../includes/atividade_logger.php';

exigir_login('admin');

// 📥 Dados do formulário
$id              = intval($_POST['id'] ?? 0);
$titulo          = trim($_POST['titulo'] ?? '');
$isbn            = trim($_POST['isbn'] ?? '');
$descricao       = trim($_POST['descricao'] ?? '');
$tipo            = $_POST['tipo'] ?? 'físico';
$formato         = $_POST['formato'] ?? 'PDF';
$link_digital    = trim($_POST['link_digital'] ?? '');
$volume          = trim($_POST['volume'] ?? '');
$edicao          = trim($_POST['edicao'] ?? '');
$codigo_interno  = trim($_POST['codigo_interno'] ?? '');

$autor_id        = is_numeric($_POST['autor_id'] ?? '') ? (int)$_POST['autor_id'] : null;
$editora_id      = is_numeric($_POST['editora_id'] ?? '') ? (int)$_POST['editora_id'] : null;
$categoria_id    = is_numeric($_POST['categoria_id'] ?? '') ? (int)$_POST['categoria_id'] : null;

// Validação básica
if ($id <= 0 || !$titulo || !$isbn || !$codigo_interno) {
    $_SESSION['erro'] = "Preencha todos os campos obrigatórios.";
    header("Location: " . URL_BASE . "frontend/admin/pages/editar_livro.php?id=$id");
    exit;
}

// 🖼️ Upload de capa (opcional)
$capa_url = null;
if (!empty($_FILES['capa']['name']) && is_uploaded_file($_FILES['capa']['tmp_name'])) {
    $permitidos = ['image/jpeg', 'image/png', 'image/webp'];
    $tipo_arquivo = mime_content_type($_FILES['capa']['tmp_name']);

    if (!in_array($tipo_arquivo, $permitidos)) {
        $_SESSION['erro'] = "Formato de imagem inválido.";
        header("Location: " . URL_BASE . "frontend/admin/pages/editar_livro.php?id=$id");
        exit;
    }

    $ext = strtolower(pathinfo($_FILES['capa']['name'], PATHINFO_EXTENSION));
    $novo_nome = uniqid('capa_', true) . '.' . $ext;
    $destino = __DIR__ . '/../../../uploads/capas/' . $novo_nome;

    if (!is_dir(dirname($destino))) {
        mkdir(dirname($destino), 0755, true);
    }

    if (move_uploaded_file($_FILES['capa']['tmp_name'], $destino)) {
        $capa_url = 'uploads/capas/' . $novo_nome;
    } else {
        $_SESSION['erro'] = "Erro ao salvar a nova capa.";
        header("Location: " . URL_BASE . "frontend/admin/pages/editar_livro.php?id=$id");
        exit;
    }
}

try {
    // Monta dinamicamente
    $sql = "UPDATE livros SET 
        titulo = :titulo,
        isbn = :isbn,
        descricao = :descricao,
        tipo = :tipo,
        formato = :formato,
        link_digital = :link_digital,
        volume = :volume,
        edicao = :edicao,
        codigo_interno = :codigo_interno,
        autor_id = :autor_id,
        editora_id = :editora_id,
        categoria_id = :categoria_id";

    if ($capa_url) {
        $sql .= ", capa_url = :capa_url";
    }

    $sql .= " WHERE id = :id";

    $stmt = $pdo->prepare($sql);

    // Parâmetros obrigatórios
    $stmt->bindValue(':titulo', $titulo);
    $stmt->bindValue(':isbn', $isbn);
    $stmt->bindValue(':descricao', $descricao);
    $stmt->bindValue(':tipo', $tipo);
    $stmt->bindValue(':formato', $formato);
    $stmt->bindValue(':link_digital', $link_digital);
    $stmt->bindValue(':volume', $volume);
    $stmt->bindValue(':edicao', $edicao);
    $stmt->bindValue(':codigo_interno', $codigo_interno);
    $stmt->bindValue(':autor_id', $autor_id);
    $stmt->bindValue(':editora_id', $editora_id);
    $stmt->bindValue(':categoria_id', $categoria_id);

    if ($capa_url) {
        $stmt->bindValue(':capa_url', $capa_url);
    }

    $stmt->bindValue(':id', $id, PDO::PARAM_INT);

    $stmt->execute();

    // ✅ Registrar atividade
    registrar_atividade($_SESSION['usuario_id'], "Livro atualizado", "Livro atualizado: $titulo (ID #$id, ISBN: $isbn)");

    $_SESSION['sucesso'] = "Livro atualizado com sucesso!";
    header("Location: " . URL_BASE . "frontend/admin/pages/editar_livro.php?id=$id");
    exit;

} catch (PDOException $e) {
    error_log("Erro ao atualizar livro ID $id: " . $e->getMessage());
    $_SESSION['erro'] = "Erro ao atualizar o livro.";
    header("Location: " . URL_BASE . "frontend/admin/pages/editar_livro.php?id=$id");
    exit;
}
