<?php
require_once __DIR__ . '/../../../backend/config/config.php';
require_once __DIR__ . '/../../../backend/includes/protect_admin.php';
require_once __DIR__ . '/../../../backend/includes/header.php';

// Estatísticas gerais
$totais = [
  'emprestimos_mes' => 0,
  'devolvidos_mes' => 0,
  'atrasados' => 0,
  'multa_total' => 0.00
];

// 🔍 Total de empréstimos e devoluções no mês atual
$mesAtual = date('Y-m');
$stmt = $pdo->prepare("SELECT 
    SUM(CASE WHEN status = 'emprestado' THEN 1 ELSE 0 END) AS emprestimos,
    SUM(CASE WHEN status = 'devolvido' THEN 1 ELSE 0 END) AS devolvidos
    FROM emprestimos WHERE DATE_FORMAT(data_emprestimo, '%Y-%m') = ?");
$stmt->execute([$mesAtual]);
$dados = $stmt->fetch();
$totais['emprestimos_mes'] = $dados['emprestimos'];
$totais['devolvidos_mes'] = $dados['devolvidos'];

// 🔍 Atrasados e multa acumulada
$stmt = $pdo->query("SELECT COUNT(*) AS atrasados, SUM(multa) AS multa_total FROM emprestimos WHERE dias_atraso > 0");
$dados = $stmt->fetch();
$totais['atrasados'] = $dados['atrasados'];
$totais['multa_total'] = $dados['multa_total'];

// 🔢 Gráfico: empréstimos por mês
$stmt = $pdo->query("
  SELECT DATE_FORMAT(data_emprestimo, '%Y-%m') AS mes, COUNT(*) AS total
  FROM emprestimos
  WHERE data_emprestimo >= DATE_SUB(CURDATE(), INTERVAL 6 MONTH)
  GROUP BY mes ORDER BY mes ASC
");
$grafico_meses = $stmt->fetchAll();

// 🔢 Gráfico: livros mais emprestados
$stmt = $pdo->query("
  SELECT l.titulo, COUNT(*) AS total
  FROM emprestimos e
  JOIN livros l ON l.id = e.livro_id
  GROUP BY l.id
  ORDER BY total DESC
  LIMIT 5
");
$grafico_livros = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <title>Painel de Empréstimos - <?= NOME_SISTEMA ?></title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body class="container mt-4">
  <h2 class="mb-4">📈 Painel de Empréstimos</h2>

  <div class="row g-4">
    <div class="col-md-3">
      <div class="card border-primary text-center">
        <div class="card-body">
          <h5 class="card-title">📚 Empréstimos do Mês</h5>
          <p class="display-6"><?= $totais['emprestimos_mes'] ?></p>
        </div>
      </div>
    </div>
    <div class="col-md-3">
      <div class="card border-success text-center">
        <div class="card-body">
          <h5 class="card-title">✅ Devoluções do Mês</h5>
          <p class="display-6"><?= $totais['devolvidos_mes'] ?></p>
        </div>
      </div>
    </div>
    <div class="col-md-3">
      <div class="card border-warning text-center">
        <div class="card-body">
          <h5 class="card-title">⏱️ Atrasados</h5>
          <p class="display-6"><?= $totais['atrasados'] ?></p>
        </div>
      </div>
    </div>
    <div class="col-md-3">
      <div class="card border-danger text-center">
        <div class="card-body">
          <h5 class="card-title">💰 Multa Acumulada</h5>
          <p class="display-6">R$ <?= number_format($totais['multa_total'], 2, ',', '.') ?></p>
        </div>
      </div>
    </div>
  </div>

  <div class="row mt-5">
    <div class="col-md-6">
      <h5>📅 Empréstimos por Mês</h5>
      <canvas id="graficoMeses"></canvas>
    </div>
    <div class="col-md-6">
      <h5>📖 Livros Mais Emprestados</h5>
      <canvas id="graficoLivros"></canvas>
    </div>
  </div>

  <script>
    const ctxMeses = document.getElementById('graficoMeses').getContext('2d');
    new Chart(ctxMeses, {
      type: 'line',
      data: {
        labels: <?= json_encode(array_column($grafico_meses, 'mes')) ?>,
        datasets: [{
          label: 'Empréstimos',
          data: <?= json_encode(array_column($grafico_meses, 'total')) ?>,
          fill: false,
          borderColor: 'blue',
          tension: 0.3
        }]
      }
    });

    const ctxLivros = document.getElementById('graficoLivros').getContext('2d');
    new Chart(ctxLivros, {
      type: 'bar',
      data: {
        labels: <?= json_encode(array_column($grafico_livros, 'titulo')) ?>,
        datasets: [{
          label: 'Qtd. Empréstimos',
          data: <?= json_encode(array_column($grafico_livros, 'total')) ?>,
          backgroundColor: 'teal'
        }]
      }
    });
  </script>
</body>
</html>
