<?php
// Caminho: backend/controllers/midias/salvar_edicao_midia.php

require_once '../../config/config.php';
require_once '../../includes/db.php';
require_once '../../includes/session.php';
require_once '../../includes/funcoes_upload.php'; // função de upload se necessário

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  header("Location: " . URL_BASE . "frontend/admin/pages/listar_midias.php");
  exit;
}

$id = (int)($_POST['id'] ?? 0);
$titulo = trim($_POST['titulo'] ?? '');
$descricao = trim($_POST['descricao'] ?? '');
$url = trim($_POST['url'] ?? '');
$tipo = trim($_POST['tipo'] ?? '');
$plataforma = trim($_POST['plataforma'] ?? '');
$data_publicacao = $_POST['data_publicacao'] ?? null;
$duracao = trim($_POST['duracao'] ?? '');
$capa_url = trim($_POST['capa_url'] ?? '');
$autor = trim($_POST['autor'] ?? '');

if (empty($id) || empty($titulo) || empty($url) || empty($tipo)) {
  $_SESSION['erro'] = "Preencha todos os campos obrigatórios.";
  header("Location: " . URL_BASE . "frontend/admin/pages/editar_midia.php?id=$id");
  exit;
}

// 🔍 Buscar a mídia atual
$stmt = $pdo->prepare("SELECT capa_local FROM midias WHERE id = ?");
$stmt->execute([$id]);
$midiaAtual = $stmt->fetch(PDO::FETCH_ASSOC);

$capa_local_atual = $midiaAtual['capa_local'] ?? null;

// 📁 Upload nova capa (se enviada)
$novo_nome_capa = $capa_local_atual;
if (!empty($_FILES['capa_local']['name'])) {
  $arquivo = $_FILES['capa_local'];
  $extensao = strtolower(pathinfo($arquivo['name'], PATHINFO_EXTENSION));
  $permitidas = ['jpg', 'jpeg', 'png', 'webp'];

  if (in_array($extensao, $permitidas)) {
    $novo_nome_capa = uniqid('capa_') . '.' . $extensao;
    $caminho = BASE_PATH . "/../storage/uploads/capas/$novo_nome_capa";

    if (move_uploaded_file($arquivo['tmp_name'], $caminho)) {
      // Exclui capa antiga se for local
      if (!empty($capa_local_atual) && file_exists(BASE_PATH . "/../storage/uploads/capas/$capa_local_atual")) {
        unlink(BASE_PATH . "/../storage/uploads/capas/$capa_local_atual");
      }
    } else {
      $_SESSION['erro'] = "Erro ao salvar nova capa.";
      header("Location: " . URL_BASE . "frontend/admin/pages/editar_midia.php?id=$id");
      exit;
    }
  } else {
    $_SESSION['erro'] = "Extensão de imagem inválida. Permitido: jpg, png, webp.";
    header("Location: " . URL_BASE . "frontend/admin/pages/editar_midia.php?id=$id");
    exit;
  }
}

try {
  $sql = "UPDATE midias SET
            titulo = :titulo,
            descricao = :descricao,
            url = :url,
            tipo = :tipo,
            plataforma = :plataforma,
            data_publicacao = :data_publicacao,
            duracao = :duracao,
            capa_url = :capa_url,
            capa_local = :capa_local,
            autor = :autor
          WHERE id = :id";

  $stmt = $pdo->prepare($sql);
  $stmt->execute([
    ':titulo' => $titulo,
    ':descricao' => $descricao,
    ':url' => $url,
    ':tipo' => $tipo,
    ':plataforma' => $plataforma,
    ':data_publicacao' => $data_publicacao ?: null,
    ':duracao' => $duracao,
    ':capa_url' => $capa_url,
    ':capa_local' => $novo_nome_capa,
    ':autor' => $autor,
    ':id' => $id
  ]);

  $_SESSION['sucesso'] = "Mídia atualizada com sucesso!";
} catch (PDOException $e) {
  $_SESSION['erro'] = "Erro ao atualizar: " . $e->getMessage();
}

header("Location: " . URL_BASE . "frontend/admin/pages/editar_midia.php?id=$id");
exit;
