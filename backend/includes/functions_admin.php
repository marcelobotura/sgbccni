<?php
// Caminho: backend/includes/functions_admin.php

function gerar_estatisticas_diarias(PDO $pdo, int $dias = 30): array {
  $sql = "
    SELECT 
      DATE(data_leitura) AS data,
      SUM(CASE WHEN status = 'lido' THEN 1 ELSE 0 END) AS livros_lidos,
      SUM(CASE WHEN status = 'favorito' THEN 1 ELSE 0 END) AS favoritos,
      (
        SELECT COUNT(*) 
        FROM comentarios 
        WHERE DATE(data_criacao) = DATE(lu.data_leitura)
      ) AS comentarios
    FROM livros_usuarios lu
    WHERE data_leitura >= CURDATE() - INTERVAL :dias DAY
    GROUP BY DATE(data_leitura)
    ORDER BY data ASC
  ";

  $stmt = $pdo->prepare($sql);
  $stmt->execute(['dias' => $dias]);
  return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function categorias_mais_lidas(PDO $pdo): array {
  $sql = "
    SELECT t.nome AS categoria, COUNT(*) AS total
    FROM livros_usuarios lu
    INNER JOIN livros l ON lu.livro_id = l.id
    LEFT JOIN tags t ON l.categoria_id = t.id
    WHERE lu.status = 'lido'
    GROUP BY t.nome
    ORDER BY total DESC
    LIMIT 5
  ";
  return $pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
}

function livros_mais_lidos(PDO $pdo, int $limite = 5): array {
  $sql = "
    SELECT l.titulo, COUNT(*) AS total
    FROM livros_usuarios lu
    INNER JOIN livros l ON lu.livro_id = l.id
    WHERE lu.status = 'lido'
    GROUP BY l.titulo
    ORDER BY total DESC
    LIMIT :limite
  ";
  $stmt = $pdo->prepare($sql);
  $stmt->bindValue(':limite', $limite, PDO::PARAM_INT);
  $stmt->execute();
  return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function leituras_por_usuario(PDO $pdo): array {
  $sql = "
    SELECT u.nome, COUNT(*) AS total
    FROM livros_usuarios lu
    INNER JOIN usuarios u ON lu.usuario_id = u.id
    WHERE lu.status = 'lido'
    GROUP BY u.nome
    ORDER BY total DESC
    LIMIT 5
  ";
  return $pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
}

function leituras_por_editora(PDO $pdo): array {
  $sql = "
    SELECT t.nome AS editora, COUNT(*) AS total
    FROM livros_usuarios lu
    INNER JOIN livros l ON lu.livro_id = l.id
    LEFT JOIN tags t ON l.editora_id = t.id
    WHERE lu.status = 'lido'
    GROUP BY t.nome
    ORDER BY total DESC
    LIMIT 5
  ";
  return $pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
}
