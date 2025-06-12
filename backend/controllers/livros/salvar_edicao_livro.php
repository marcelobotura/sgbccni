<?php
session_start();
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../includes/protect_admin.php';

exigir_login('admin');

// 🔒 Valida entrada
if (!isset($_POST['id'])) {
  $_SESSION['erro'] = 'ID do livro não fornecido.';
  header('Location: ' . URL_BASE . 'frontend/admin/pages/listar_livros.php');
  exit;
}

// 📥 Recebe dados do formulário
$id = intval($_POST['id']);
$titulo = trim($_POST['titulo'] ?? '');
$isbn = trim($_POST['isbn'] ?? '');
$tipo = trim($_POST['tipo'] ?? '');
$formato = trim($_POST['formato'] ?? '');
$link_digital = trim($_POST['link_digital'] ?? '');
$descricao = trim($_POST['descricao'] ?? '');
$volume = trim($_POST['volume'] ?? '');
$edicao = trim($_POST['edicao'] ?? '');
$codigo_interno = trim($_POST['codigo_interno'] ?? '');
$autor_input = trim($_POST['autor_id'] ?? '');
$editora_input = trim($_POST['editora_id'] ?? '');
$categoria_input = trim($_POST['categoria_id'] ?? '');

// 🚨 Validação básica
if (empty($titulo) || empty($isbn) || empty($codigo_interno)) {
  $_SESSION['erro'] = 'Preencha todos os campos obrigatórios.';
  header('Location: ' . URL_BASE . 'frontend/admin/pages/editar_livro.php?id=' . $id);
  exit;
}

// 🔁 Função para obter ou criar tag
function obterOuCriarTag($conn, $valor, $tipo) {
  if (is_numeric($valor)) {
    $stmt = $conn->prepare("SELECT id FROM tags WHERE id = :id AND tipo = :tipo");
    $stmt->execute([':id' => $valor, ':tipo' => $tipo]);
    return $stmt->fetchColumn() ?: null;
  }

  $stmt = $conn->prepare("SELECT id FROM tags WHERE nome = :nome AND tipo = :tipo");
  $stmt->execute([':nome' => $valor, ':tipo' => $tipo]);
  $id = $stmt->fetchColumn();
  if ($id) return $id;

  $stmt = $conn->prepare("INSERT INTO tags (nome, tipo) VALUES (:nome, :tipo)");
  $stmt->execute([':nome' => $valor, ':tipo' => $tipo]);
  return $conn->lastInsertId();
}

// 🔄 Processa tags dinamicamente
$autor_id = obterOuCriarTag($conn, $autor_input, 'autor');
$editora_id = obterOuCriarTag($conn, $editora_input, 'editora');
$categoria_id = obterOuCriarTag($conn, $categoria_input, 'categoria');

// 📝 Atualiza no banco
$sql = "UPDATE livros SET 
          titulo = :titulo,
          isbn = :isbn,
          tipo = :tipo,
          formato = :formato,
          link_digital = :link_digital,
          descricao = :descricao,
          volume = :volume,
          edicao = :edicao,
          codigo_interno = :codigo_interno,
          autor_id = :autor_id,
          editora_id = :editora_id,
          categoria_id = :categoria_id
        WHERE id = :id";

$stmt = $conn->prepare($sql);
$stmt->bindParam(':id', $id);
$stmt->bindParam(':titulo', $titulo);
$stmt->bindParam(':isbn', $isbn);
$stmt->bindParam(':tipo', $tipo);
$stmt->bindParam(':formato', $formato);
$stmt->bindParam(':link_digital', $link_digital);
$stmt->bindParam(':descricao', $descricao);
$stmt->bindParam(':volume', $volume);
$stmt->bindParam(':edicao', $edicao);
$stmt->bindParam(':codigo_interno', $codigo_interno);
$stmt->bindParam(':autor_id', $autor_id);
$stmt->bindParam(':editora_id', $editora_id);
$stmt->bindParam(':categoria_id', $categoria_id);

try {
  $stmt->execute();
  $_SESSION['sucesso'] = 'Livro atualizado com sucesso!';
} catch (PDOException $e) {
  $_SESSION['erro'] = 'Erro ao atualizar o livro: ' . $e->getMessage();
}

header('Location: ' . URL_BASE . 'frontend/admin/pages/listar_livros.php');
exit;
