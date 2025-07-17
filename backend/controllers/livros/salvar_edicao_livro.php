<?php
define('BASE_PATH', dirname(__DIR__, 2)); // vai até /backend
require_once BASE_PATH . '/config/config.php';
require_once BASE_PATH . '/includes/session.php';
require_once BASE_PATH . '/includes/protect_admin.php';
require_once BASE_PATH . '/includes/atividade_logger.php';

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
    header("Location: " . URL_BASE . "frontend/admin/pages/editar_livro.php?id=$id");
    exit;
}

// 📁 Upload de nova capa (opcional)
$capa_url = null;
if (!empty($_FILES['nova_capa']['name'])) {
    $permitidos = ['image/jpeg', 'image/png', 'image/webp'];
    $tipo_arquivo = mime_content_type($_FILES['nova_capa']['tmp_name']);

    if (!in_array($tipo_arquivo, $permitidos)) {
        $_SESSION['erro'] = "Formato de imagem não permitido.";
        header("Location: " . URL_BASE . "frontend/admin/pages/editar_livro.php?id=$id");
        exit;
    }

    $ext = pathinfo($_FILES['nova_capa']['name'], PATHINFO_EXTENSION);
    $nome_arquivo = uniqid('capa_', true) . '.' . $ext;
    $destino = BASE_PATH . '/../uploads/capas/' . $nome_arquivo;

    if (!is_dir(dirname($destino))) {
        mkdir(dirname($destino), 0755, true);
    }

    if (move_uploaded_file($_FILES['nova_capa']['tmp_name'], $destino)) {
        $capa_url = 'uploads/capas/' . $nome_arquivo;
    } else {
        $_SESSION['erro'] = "Erro ao salvar a imagem.";
        header("Location: " . URL_BASE . "frontend/admin/pages/editar_livro.php?id=$id");
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

    if ($capa_url) {
        $sql .= ", capa_url = :capa_url";
    }

    $sql .= " WHERE id = :id";

    $stmt = $pdo->prepare($sql);
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
    if ($capa_url) {
        $stmt->bindValue(':capa_url', $capa_url);
    }
    $stmt->bindValue(':id', $id, PDO::PARAM_INT);

    $stmt->execute();

    // 📝 Registrar atividade
    registrar_atividade($pdo, "Livro editado: ID #$id - $titulo (ISBN: $isbn)", $_SESSION['usuario_id']);

    $_SESSION['sucesso'] = "Livro atualizado com sucesso!";
    header("Location: " . URL_BASE . "frontend/admin/pages/listar_livros.php");
    exit;
} catch (PDOException $e) {
    $_SESSION['erro'] = "Erro ao atualizar livro: " . $e->getMessage();
    header("Location: " . URL_BASE . "frontend/admin/pages/editar_livro.php?id=$id");
    exit;
}
