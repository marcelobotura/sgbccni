<?php
// Caminhos corrigidos para config.php, session.php e auth.php
require_once __DIR__ . '/../../config/config.php';   // Sobe para 'backend', depois entra em 'config'
require_once __DIR__ . '/../../includes/session.php'; // Sobe para 'backend', depois entra em 'includes'
require_once __DIR__ . '/auth.php';                  // Está no mesmo diretório 'controllers/livros'

// Autoload do Composer (Endroid QR Code) - CORRIGIDO
// De 'backend/controllers/livros' você precisa subir 3 níveis para chegar na raiz 'sgbccni',
// e depois entrar em 'vendor'.
require_once __DIR__ . '/../../../vendor/autoload.php';

use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Writer\PngWriter;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel\ErrorCorrectionLevelLow;

exigir_login('admin'); // Garante que apenas admins possam acessar

// --- Coleta e Sanitização dos Dados ---
$titulo          = trim($_POST['titulo'] ?? '');
$isbn            = trim($_POST['isbn'] ?? '');
$descricao       = trim($_POST['descricao'] ?? '');
$tipo            = $_POST['tipo'] ?? 'físico';
$formato         = $_POST['formato'] ?? 'PDF';
$link_digital    = trim($_POST['link_digital'] ?? '');
$volume          = trim($_POST['volume'] ?? '');
$edicao          = trim($_POST['edicao'] ?? '');
$codigo_interno  = trim($_POST['codigo_interno'] ?? '');

$autor_id        = filter_var($_POST['autor_id'] ?? null, FILTER_VALIDATE_INT);
$editora_id      = filter_var($_POST['editora_id'] ?? null, FILTER_VALIDATE_INT);
$categoria_id    = filter_var($_POST['categoria_id'] ?? null, FILTER_VALIDATE_INT);

// --- Validação Mínima ---
if (empty($titulo) || empty($isbn) || empty($codigo_interno)) {
    $_SESSION['erro'] = "Preencha os campos obrigatórios: Título, ISBN e Código Interno.";
    header("Location: " . URL_BASE . "admin/pages/cadastrar_livro.php");
    exit;
}

// --- Validação de Duplicação ---
$stmt_check = $conn->prepare("SELECT id FROM livros WHERE isbn = ? OR codigo_interno = ?");
if (!$stmt_check) {
    $_SESSION['erro'] = "Erro de preparação da consulta de verificação: " . $conn->error;
    header("Location: " . URL_BASE . "admin/pages/cadastrar_livro.php");
    exit;
}
$stmt_check->bind_param("ss", $isbn, $codigo_interno);
$stmt_check->execute();
$stmt_check->store_result();

if ($stmt_check->num_rows > 0) {
    $_SESSION['erro'] = "ISBN ou código interno já cadastrado. Por favor, verifique.";
    $stmt_check->close();
    header("Location: " . URL_BASE . "admin/pages/cadastrar_livro.php");
    exit;
}
$stmt_check->close();

// --- Upload da Capa ---
$capa_local = '';
if (isset($_FILES['capa']) && $_FILES['capa']['error'] === UPLOAD_ERR_OK) {
    $permitidos = ['image/jpeg', 'image/png', 'image/webp'];
    $tipo_img = mime_content_type($_FILES['capa']['tmp_name']);

    if (!in_array($tipo_img, $permitidos)) {
        $_SESSION['erro'] = "Formato de imagem não permitido para a capa. Use JPEG, PNG ou WebP.";
        header("Location: " . URL_BASE . "admin/pages/cadastrar_livro.php");
        exit;
    }

    $ext = pathinfo($_FILES['capa']['name'], PATHINFO_EXTENSION);
    $novo_nome = uniqid('capa_', true) . '.' . $ext;
    // Caminho absoluto para salvar a capa:
    // De 'backend/controllers/livros' para 'public_html/uploads/capas'
    $destino = __DIR__ . '/../../../public_html/uploads/capas/' . $novo_nome;

    if (!file_exists(dirname($destino))) {
        mkdir(dirname($destino), 0777, true);
    }

    if (move_uploaded_file($_FILES['capa']['tmp_name'], $destino)) {
        $capa_local = 'uploads/capas/' . $novo_nome; // Caminho relativo para o banco de dados (a partir de public_html)
    } else {
        $_SESSION['erro'] = "Erro ao salvar o arquivo da capa.";
        header("Location: " . URL_BASE . "admin/pages/cadastrar_livro.php");
        exit;
    }
}

// --- Geração do QR Code com Endroid ---
$link_qr = URL_BASE . 'livro.php?codigo=' . urlencode($codigo_interno);
$qr_file = uniqid('qr_') . '.png';
// Caminho absoluto para salvar o QR Code dentro de public_html:
// De 'backend/controllers/livros' para 'public_html/uploads/qrcodes'
$qr_path = __DIR__ . '/../../../public_html/uploads/qrcodes/' . $qr_file;

if (!file_exists(dirname($qr_path))) {
    mkdir(dirname($qr_path), 0777, true);
}

try {
    Builder::create()
        ->writer(new PngWriter())
        ->data($link_qr)
        ->encoding(new Encoding('UTF-8'))
        ->errorCorrectionLevel(new ErrorCorrectionLevelLow())
        ->size(300)
        ->margin(10)
        ->build()
        ->saveToFile($qr_path);

    $qr_code_relativo = 'uploads/qrcodes/' . $qr_file; // Caminho relativo para o banco de dados
} catch (Exception $e) {
    $_SESSION['erro'] = "Erro ao gerar o QR Code: " . $e->getMessage();
    header("Location: " . URL_BASE . "admin/pages/cadastrar_livro.php");
    exit;
}

// --- Salvar no Banco de Dados ---
$stmt = $conn->prepare("INSERT INTO livros (
    titulo, isbn, descricao, tipo, formato, link_digital, capa_local,
    autor_id, editora_id, categoria_id, volume, edicao, codigo_interno, qr_code
) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

if (!$stmt) {
    $_SESSION['erro'] = "Erro de preparação da consulta de inserção: " . $conn->error;
    header("Location: " . URL_BASE . "admin/pages/cadastrar_livro.php");
    exit;
}

$stmt->bind_param(
    "sssssssiiissss",
    $titulo, $isbn, $descricao, $tipo, $formato, $link_digital, $capa_local,
    $autor_id, $editora_id, $categoria_id, $volume, $edicao, $codigo_interno, $qr_code_relativo
);

if ($stmt->execute()) {
    $_SESSION['sucesso'] = "Livro cadastrado com sucesso!";
} else {
    $_SESSION['erro'] = "Erro ao salvar o livro no banco de dados: " . $stmt->error;
}

$stmt->close();
header("Location: " . URL_BASE . "admin/pages/cadastrar_livro.php");
exit;