<?php
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../includes/session.php';
require_once __DIR__ . '/../../includes/atividade_logger.php';
exigir_login('admin');


// Função para criar tag se não existir
function definirTagID($pdo, $tipo, $nome) {
    if (!$nome) return null;
    
    // Verifica se já existe
    $stmt = $pdo->prepare("SELECT id FROM tags WHERE nome = ? AND tipo = ?");
    $stmt->execute([$nome, $tipo]);
    $tag = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($tag) return $tag['id'];

    // Cria nova tag
    $stmt = $pdo->prepare("INSERT INTO tags (nome, tipo) VALUES (?, ?)");
    $stmt->execute([$nome, $tipo]);
    return $pdo->lastInsertId();
}

// Dados obrigatórios
$titulo          = trim($_POST['titulo'] ?? '');
$isbn            = trim($_POST['isbn'] ?? '');
$codigoInterno   = trim($_POST['codigo_interno'] ?? '');

if (empty($titulo) || empty($isbn) || empty($codigoInterno)) {
    $_SESSION['erro'] = 'Preencha todos os campos obrigatórios';
    header('Location: ' . URL_BASE . 'frontend/admin/pages/cadastrar_livro.php');
    exit;
}

// Demais dados
$subtitulo       = trim($_POST['subtitulo'] ?? '');
$descricao       = trim($_POST['descricao'] ?? '');
$volume          = trim($_POST['volume'] ?? '');
$edicao          = trim($_POST['edicao'] ?? '');
$ano             = trim($_POST['ano'] ?? '');
$idioma          = trim($_POST['idioma'] ?? '');
$tipo            = $_POST['tipo'] ?? 'físico';
$formato         = $_POST['formato'] ?? '';
$linkDigital     = trim($_POST['link_digital'] ?? '');
$isbn10          = trim($_POST['isbn10'] ?? '');
$codigoBarras    = trim($_POST['codigo_barras'] ?? '');
$fonte           = trim($_POST['fonte'] ?? 'Manual');

// Tags (podem vir como texto novo)
$autor           = trim($_POST['autor_id'] ?? '');
$editora         = trim($_POST['editora_id'] ?? '');
$categoria       = trim($_POST['categoria_id'] ?? '');

$autorID = definirTagID($pdo, 'autor', $autor);
$editoraID = definirTagID($pdo, 'editora', $editora);
$categoriaID = definirTagID($pdo, 'categoria', $categoria);

// Upload da capa
$capaArquivo = $_FILES['capa'] ?? null;
$capaNome = null;
if ($capaArquivo && $capaArquivo['error'] === UPLOAD_ERR_OK) {
    $ext = pathinfo($capaArquivo['name'], PATHINFO_EXTENSION);
    $capaNome = uniqid('capa_', true) . '.' . strtolower($ext);
    $destino = __DIR__ . '/../../../uploads/capas/' . $capaNome;
    if (!move_uploaded_file($capaArquivo['tmp_name'], $destino)) {
        $_SESSION['erro'] = 'Erro ao salvar a capa do livro.';
        header('Location: ' . URL_BASE . 'frontend/admin/pages/cadastrar_livro.php');
        exit;
    }
}

// Inserir no banco
try {
    $stmt = $pdo->prepare("INSERT INTO livros (
        titulo, subtitulo, descricao, volume, edicao, ano, idioma,
        isbn, isbn10, codigo_barras, codigo_interno,
        tipo, formato, link_digital, fonte,
        autor_id, editora_id, categoria_id, capa
    ) VALUES (
        :titulo, :subtitulo, :descricao, :volume, :edicao, :ano, :idioma,
        :isbn, :isbn10, :codigo_barras, :codigo_interno,
        :tipo, :formato, :link_digital, :fonte,
        :autor_id, :editora_id, :categoria_id, :capa
    )");

    $stmt->execute([
        ':titulo' => $titulo,
        ':subtitulo' => $subtitulo,
        ':descricao' => $descricao,
        ':volume' => $volume,
        ':edicao' => $edicao,
        ':ano' => $ano,
        ':idioma' => $idioma,
        ':isbn' => $isbn,
        ':isbn10' => $isbn10,
        ':codigo_barras' => $codigoBarras,
        ':codigo_interno' => $codigoInterno,
        ':tipo' => $tipo,
        ':formato' => $formato,
        ':link_digital' => $linkDigital,
        ':fonte' => $fonte,
        ':autor_id' => $autorID,
        ':editora_id' => $editoraID,
        ':categoria_id' => $categoriaID,
        ':capa' => $capaNome
    ]);


        // Registra atividade se a função existir
    if (function_exists('registrar_atividade')) {
        registrar_atividade($pdo, $_SESSION['usuario_id'], "Livro cadastrado: $titulo (ISBN: $isbn)");
    }

    $_SESSION['sucesso'] = 'Livro cadastrado com sucesso!';
} catch (PDOException $e) {
    $_SESSION['erro'] = 'Erro ao salvar o livro: ' . $e->getMessage();
}

// Redireciona de volta para o formulário
header('Location: ' . URL_BASE . 'frontend/admin/pages/cadastrar_livro.php');
exit;
