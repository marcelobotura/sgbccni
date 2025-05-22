<?php
include_once(__DIR__ . '/../config/config.php'); // ✅ novo caminho



$q = trim($_GET['q'] ?? '');

if (strlen($q) < 2) {
  echo json_encode([]);
  exit;
}

$sql = "SELECT DISTINCT titulo FROM livros WHERE titulo LIKE ? OR autor LIKE ? LIMIT 10";
$stmt = $conn->prepare($sql);
$busca = "%{$q}%";
$stmt->bind_param("ss", $busca, $busca);
$stmt->execute();
$result = $stmt->get_result();

$sugestoes = [];
while ($row = $result->fetch_assoc()) {
  $sugestoes[] = $row['titulo'];
}

echo json_encode($sugestoes);
?>
