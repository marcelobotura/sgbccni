<?php
include_once(__DIR__ . '/../config/config.php');

// Recebe a consulta do usuário
$q = trim($_GET['q'] ?? '');

if (strlen($q) < 2) {
  header('Content-Type: application/json');
  echo json_encode([]);
  exit;
}

$busca = '%' . $q . '%';
$sql = "SELECT DISTINCT titulo FROM livros 
        WHERE titulo COLLATE utf8_general_ci LIKE ? 
           OR autor COLLATE utf8_general_ci LIKE ? 
        LIMIT 10";

$stmt = $conn->prepare($sql);

if (!$stmt) {
  http_response_code(500);
  echo json_encode(["Erro na preparação da consulta: " . $conn->error]);
  exit;
}

$stmt->bind_param("ss", $busca, $busca);
$stmt->execute();
$result = $stmt->get_result();

$sugestoes = [];
while ($row = $result->fetch_assoc()) {
  $sugestoes[] = $row['titulo'];
}

header('Content-Type: application/json');
echo json_encode($sugestoes);
