<?php
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../includes/session.php';
require_once __DIR__ . '/../../includes/atividade_logger.php';
exigir_login('admin');

// Função para buscar ou criar uma tag
function definirTagID($pdo, $tipo, $valor) {
    if (!$valor) return null;

    // Se for ID válido, apenas retorna
    if (is_numeric($valor)) return $valor;

    // Verifica se já existe
    $stmt = $pdo->prepare("SELECT id FROM tags WHERE nome = ? AND tipo = ?");
    $stmt->execute([$valor, $tipo]);
    $tag = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($tag) return $tag['id'];

    // Cria nova
    $stmt = $pdo->prepare("INSERT INTO tags (nome, tipo) VALUES (?, ?)");
    $stmt->execute([$valor, $tipo]);
    return $pdo->lastInsertId();
}

// Verifica ID
$id = intval($_POST['id'] ?? 0);
if ($id <= 0) {
    $_SESSION['erro'] = 'ID inválido.';
    header('Location: ' . URL_BASE . 'frontend/admin/pages/gerenciar_livros.php');
    exit;
}

// Campos do formulário
$titulo        = trim($_POST['titulo'] ?? '');
$codigoInterno = trim($_POST['codigo_interno'] ?? '');
$isbn          = trim($_POST['isbn'] ?? '');
$isbn10        = trim($_POST['isbn10'] ?? '');
$codigoBarras  = trim($_POST['codigo_barras'] ?? '');
$subtitulo     = trim($_POST['subtitulo'] ?? '');
$descricao     = trim($_POST['descricao'] ?? '');
$volume        = trim($_POST['volume'] ?? '');
$edicao        = trim($_POST['edicao'] ?? '');
$ano           = trim($_POST['ano'] ?? '');
$idioma        = trim($_POST['idioma'] ?? '');
$linkDigital   = trim($_POST['link_digital'] ?? '');
$tipo          = $_POST['tipo'] ?? 'físico';
$formato       = $_POST['formato'] ?? '';
$autor         = $_POST['autor_id'] ?? '';
$editora       = $_POST['editora_id'] ?? '';
$categoria     = $_POST['categoria_id'] ?? '';

// Validação básica
if (!$titulo || !$codigoInterno) {
    $_SESSION['erro'] = 'Título e código interno são obrigatórios.';
    header('Location: ' . URL_BASE . 'frontend/admin/pages/editar_livro.php?id=' . $id);
    exit;
}

// Define tags
$autorID = definirTagID($pdo, 'autor', $autor);
$editoraID = definirTagID($pdo, 'editora', $editora);
$categoriaID = definirTagID($pdo, 'categoria', $categoria);

// Processa nova capa se houver
$novaCapa = $_FILES['nova_capa'] ?? null;
$capa_local = null;
$origem_capa = null;

if ($novaCapa && $novaCapa['error'] === UPLOAD_ERR_OK) {
    $ext = strtolower(pathinfo($novaCapa['name'], PATHINFO_EXTENSION));
    $nomeArquivo = 'capa_' . uniqid() . '.' . $ext;
    $destino = __DIR__ . '/../../../uploads/capas/' . $nomeArquivo;

    if (move_uploaded_file($novaCapa['tmp_name'], $destino)) {
        $capa_local = 'uploads/capas/' . $nomeArquivo;
        $origem_capa = 'upload';
    } else {
        $_SESSION['erro'] = 'Erro ao fazer upload da nova capa.';
        header('Location: ' . URL_BASE . 'frontend/admin/pages/editar_livro.php?id=' . $id);
        exit;
    }
}

// Monta UPDATE
$sql = "UPDATE livros SET
    titulo = :titulo,
    codigo_interno = :codigo_interno,
    isbn = :isbn,
    isbn10 = :isbn10,
    codigo_barras = :codigo_barras,
    subtitulo = :subtitulo,
    descricao = :descricao,
    volume = :volume,
    edicao = :edicao,
    ano = :ano,
    idioma = :idioma,
    tipo = :tipo,
    formato = :formato,
    link_digital = :link_digital,
    autor_id = :autor_id,
    editora_id = :editora_id,
    categoria_id = :categoria_id";

// Capa (opcional)
if ($capa_local) {
    $sql .= ", capa_local = :capa_local, origem_capa = :origem_capa";
}

$sql .= " WHERE id = :id";

// Executa
try {
    $stmt = $pdo->prepare($sql);
    $params = [
        ':titulo' => $titulo,
        ':codigo_interno' => $codigoInterno,
        ':isbn' => $isbn,
        ':isbn10' => $isbn10,
        ':codigo_barras' => $codigoBarras,
        ':subtitulo' => $subtitulo,
        ':descricao' => $descricao,
        ':volume' => $volume,
        ':edicao' => $edicao,
        ':ano' => $ano,
        ':idioma' => $idioma,
        ':tipo' => $tipo,
        ':formato' => $formato,
        ':link_digital' => $linkDigital,
        ':autor_id' => $autorID,
        ':editora_id' => $editoraID,
        ':categoria_id' => $categoriaID,
        ':id' => $id
    ];

    if ($capa_local) {
        $params[':capa_local'] = $capa_local;
        $params[':origem_capa'] = $origem_capa;
    }

    $stmt->execute($params);

    if (function_exists('registrar_atividade')) {
        registrar_atividade($pdo, $_SESSION['usuario_id'], "Atualizou os dados do livro ID $id");
    }

    $_SESSION['sucesso'] = 'Livro atualizado com sucesso.';
    header('Location: ' . URL_BASE . 'frontend/admin/pages/editar_livro.php?id=' . $id);
    exit;
} catch (PDOException $e) {
    $_SESSION['erro'] = 'Erro ao atualizar o livro: ' . $e->getMessage();
    header('Location: ' . URL_BASE . 'frontend/admin/pages/editar_livro.php?id=' . $id);
    exit;
}
