<?php
// Caminho: backend/controllers/midias/salvar_midia.php

define('BASE_PATH', dirname(__DIR__, 3)); // ✅ necessário para usar BASE_PATH corretamente

require_once BASE_PATH . '/backend/config/config.php';
require_once BASE_PATH . '/backend/includes/db.php';
require_once BASE_PATH . '/backend/includes/session.php';

// Validação básica
$titulo = trim($_POST['titulo'] ?? '');
$url = trim($_POST['url'] ?? '');
$tipo = $_POST['tipo'] ?? '';

if (empty($titulo) || empty($url) || empty($tipo)) {
  $_SESSION['erro'] = "Preencha os campos obrigatórios.";
  header('Location: ' . URL_BASE . 'frontend/admin/pages/cadastrar_midia.php');
  exit;
}

$descricao       = trim($_POST['descricao'] ?? '');
$plataforma      = trim($_POST['plataforma'] ?? '');
$data_publicacao = $_POST['data_publicacao'] ?? null;
$duracao         = trim($_POST['duracao'] ?? '');
$capa_url        = trim($_POST['capa_url'] ?? '');
$autor           = trim($_POST['autor'] ?? '');

// 📸 Upload da capa (prioritário se enviado)
$capa_local = '';
if (!empty($_FILES['capa_upload']['name'])) {
  $extensao = strtolower(pathinfo($_FILES['capa_upload']['name'], PATHINFO_EXTENSION));
  $permitidas = ['jpg', 'jpeg', 'png', 'gif', 'webp'];

  if (!in_array($extensao, $permitidas)) {
    $_SESSION['erro'] = "Formato de imagem não permitido.";
    header('Location: ' . URL_BASE . 'frontend/admin/pages/cadastrar_midia.php');
    exit;
  }

  $nomeArquivo = 'capa_' . time() . '_' . uniqid() . '.' . $extensao;
  $dirCapas = BASE_PATH . '/storage/uploads/capas/';
  $caminhoFinal = $dirCapas . $nomeArquivo;

  if (!is_dir($dirCapas)) {
    mkdir($dirCapas, 0755, true);
  }

  if (move_uploaded_file($_FILES['capa_upload']['tmp_name'], $caminhoFinal)) {
    $capa_local = $nomeArquivo;
    $capa_url = ''; // Anula a URL se houve upload local
  } else {
    $_SESSION['erro'] = "Erro ao fazer upload da capa.";
    header('Location: ' . URL_BASE . 'frontend/admin/pages/cadastrar_midia.php');
    exit;
  }
}

try {
  $stmt = $pdo->prepare("INSERT INTO midias 
    (tipo, titulo, descricao, url, plataforma, data_publicacao, duracao, capa_url, capa_local, autor)
    VALUES 
    (:tipo, :titulo, :descricao, :url, :plataforma, :data_publicacao, :duracao, :capa_url, :capa_local, :autor)");

  $stmt->execute([
    ':tipo'            => $tipo,
    ':titulo'          => $titulo,
    ':descricao'       => $descricao,
    ':url'             => $url,
    ':plataforma'      => $plataforma,
    ':data_publicacao' => $data_publicacao ?: null,
    ':duracao'         => $duracao,
    ':capa_url'        => $capa_url,
    ':capa_local'      => $capa_local,
    ':autor'           => $autor
  ]);

  $_SESSION['sucesso'] = "✅ Mídia cadastrada com sucesso!";
} catch (PDOException $e) {
  $_SESSION['erro'] = "Erro ao salvar: " . $e->getMessage();
}

header('Location: ' . URL_BASE . 'frontend/admin/pages/cadastrar_midia.php');
exit;
