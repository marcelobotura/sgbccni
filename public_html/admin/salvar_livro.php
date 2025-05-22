<?php
// ✅ Inicia a sessão, se ainda não estiver iniciada
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 🔧 Define o caminho base e inclui configurações e funções
define('BASE_PATH', dirname(__DIR__, 2) . '/app_backend');
require_once BASE_PATH . '/config/config.php';
require_once BASE_PATH . '/includes/session.php'; // Inclua seu arquivo de funções de sessão aqui

// 📌 Função para obter ou criar a tag (autor, editora, categoria)
function obterOuCriarTag($conn, $nome, $tipo) {
    // Limpar e validar o nome para evitar tags vazias ou maliciosas
    $nome = trim($nome);
    if (empty($nome)) {
        return null; // Retorna null se o nome for vazio
    }

    // Tenta encontrar a tag existente
    $stmt = $conn->prepare("SELECT id FROM tags WHERE nome = ? AND tipo = ?");
    if (!$stmt) {
        error_log("Erro ao preparar SELECT tag: " . $conn->error);
        return null;
    }
    $stmt->bind_param("ss", $nome, $tipo);
    $stmt->execute();
    $stmt->store_result(); // Armazena o resultado para poder usar num_rows

    if ($stmt->num_rows > 0) {
        // Se a tag existe, obtém o ID
        $stmt->bind_result($id); // Somente bind se houver resultados
        $stmt->fetch();
        $stmt->close(); // Fecha o statement
        return $id;
    } else {
        // Se a tag não existe, insere uma nova
        $stmt->close(); // Fecha o statement anterior antes de criar um novo

        $stmt = $conn->prepare("INSERT INTO tags (nome, tipo) VALUES (?, ?)");
        if (!$stmt) {
            error_log("Erro ao preparar INSERT tag: " . $conn->error);
            return null;
        }
        $stmt->bind_param("ss", $nome, $tipo);
        if ($stmt->execute()) { // Verifica se a execução foi bem-sucedida
            $id = $stmt->insert_id;
            $stmt->close(); // Fecha o statement
            return $id;
        } else {
            error_log("Erro ao criar tag: " . $stmt->error); // Loga o erro
            $stmt->close(); // Fecha o statement
            return null; // Retorna null em caso de falha na inserção
        }
    }
}

// 📥 Captura e sanitiza os dados do formulário
$titulo          = htmlspecialchars(trim($_POST['titulo'] ?? ''));
$autor_nome      = htmlspecialchars(trim($_POST['autor_nome'] ?? ''));
$editora_nome    = htmlspecialchars(trim($_POST['editora_nome'] ?? ''));
$categoria_nome  = htmlspecialchars(trim($_POST['categoria_nome'] ?? ''));
$formato         = htmlspecialchars(trim($_POST['formato'] ?? 'físico'));
// Valida URL para link_leitura e capa_url. Se inválido, será null.
$link_leitura    = filter_var(trim($_POST['link_leitura'] ?? ''), FILTER_VALIDATE_URL) ?: null;
$isbn            = htmlspecialchars(str_replace('-', '', trim($_POST['isbn'] ?? ''))); // Remove hífens do ISBN
$descricao       = htmlspecialchars(trim($_POST['descricao'] ?? ''));
$idioma          = htmlspecialchars(trim($_POST['idioma'] ?? ''));
// Validação para ano e páginas: garante que são inteiros e dentro de um intervalo razoável. Se falhar, é null.
$ano             = filter_var(trim($_POST['ano'] ?? ''), FILTER_VALIDATE_INT, ['options' => ['min_range' => 1000, 'max_range' => date('Y')]]) ?: null;
$paginas         = filter_var(trim($_POST['paginas'] ?? ''), FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]) ?: null;
$capa_url        = filter_var(trim($_POST['capa_url'] ?? ''), FILTER_VALIDATE_URL) ?: null;

// 🚨 Validação básica dos campos obrigatórios
if (empty($titulo) || empty($autor_nome) || empty($editora_nome) || empty($categoria_nome)) {
    $_SESSION['erro'] = "Erro: Título, Autor, Editora e Categoria são campos obrigatórios.";
    header("Location: cadastrar_livro.php");
    exit;
}

// 📌 Gera IDs das tags
$autor_id        = obterOuCriarTag($conn, $autor_nome, 'autor');
$editora_id      = obterOuCriarTag($conn, $editora_nome, 'editora');
$categoria_id    = obterOuCriarTag($conn, $categoria_nome, 'categoria');

// Verifica se as tags foram criadas/obtidas com sucesso (se obterOuCriarTag retornou null)
if (is_null($autor_id) || is_null($editora_id) || is_null($categoria_id)) {
    $_SESSION['erro'] = "Erro ao processar as tags (autor, editora, categoria). Verifique se os nomes não estão vazios ou se houve um problema no banco de dados.";
    header("Location: cadastrar_livro.php");
    exit;
}

// 📚 Processamento de dados automáticos
$isbn10          = substr($isbn, 0, 10);
$isbn13          = substr($isbn, 0, 13);
$numero_interno  = 'LIV-' . uniqid(mt_rand(), true);
$codigo_barras   = !empty($isbn) ? $isbn : rand(1000000000000, 9999999999999);
$qr_code         = URL_BASE . "livro.php?isbn=" . urlencode($isbn);

// 📤 Insere o livro no banco
$stmt = $conn->prepare("INSERT INTO livros
    (titulo, autor_id, editora_id, categoria_id, ano, paginas, idioma, descricao, capa_url,
     isbn, isbn10, isbn13, numero_interno, codigo_barras, qr_code,
     formato, link_leitura, tipo, copias_disponiveis, exemplares, visualizacoes, criado_em)
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'livro', 1, 1, 0, NOW())");

if (!$stmt) {
    $_SESSION['erro'] = "Erro ao preparar INSERT livro: " . $conn->error;
    error_log("Erro ao preparar INSERT livro: " . $conn->error);
    header("Location: cadastrar_livro.php");
    exit;
}

$stmt->bind_param("siiisssssssssssss",
    $titulo,
    $autor_id,
    $editora_id,
    $categoria_id,
    $ano,
    $paginas,
    $idioma,
    $descricao,
    $capa_url,
    $isbn,
    $isbn10,
    $isbn13,
    $numero_interno,
    $codigo_barras,
    $qr_code,
    $formato,
    $link_leitura
);

// 🎯 Resultado
if ($stmt->execute()) {
    $_SESSION['sucesso'] = "✅ Livro salvo com sucesso!";
} else {
    $_SESSION['erro'] = "Erro ao salvar o livro: " . $stmt->error;
    error_log("Erro no salvar_livro.php: " . $stmt->error); // Loga o erro para depuração
}

$stmt->close(); // Fecha o statement principal

// 🔁 Redireciona de volta para o formulário
header("Location: cadastrar_livro.php");
exit;
?>