<?php
// Caminho: frontend/admin/pages/dashboard.php

define('BASE_PATH', dirname(__DIR__, 3));
require_once BASE_PATH . '/backend/config/config.php';
require_once BASE_PATH . '/backend/includes/session.php';
require_once BASE_PATH . '/backend/includes/protect_admin.php';
require_once BASE_PATH . '/backend/includes/functions_admin.php';
require_once BASE_PATH . '/backend/includes/header.php';
require_once BASE_PATH . '/backend/includes/menu.php';

exigir_login('admin');

$periodo = isset($_GET['dias']) ? (int) $_GET['dias'] : 30;
if (!in_array($periodo, [7, 15, 30])) {
  $periodo = 30;
}

$estatisticas = gerar_estatisticas_diarias($pdo, $periodo);
$datas = array_column($estatisticas, 'data');
$livrosLidos = array_column($estatisticas, 'livros_lidos');
$favoritos = array_column($estatisticas, 'favoritos');
$comentarios = array_column($estatisticas, 'comentarios');

$categorias = categorias_mais_lidas($pdo);
$maisLidos = livros_mais_lidos($pdo, 5);
$leiturasPorUsuario = leituras_por_usuario($pdo);
$leiturasPorEditora = leituras_por_editora($pdo);
$categoriaMaisLida = $categorias[0]['categoria'] ?? 'N/A';
$totalLeituras = array_sum($livrosLidos);
$mediaDiaria = $totalLeituras > 0 ? round($totalLeituras / count($livrosLidos), 2) : 0;
?>
<div class="container py-4">
  <h2 class="mb-4"><i class="bi bi-speedometer2"></i> Painel de Estatísticas</h2>

  <!-- 🔍 Filtro de período -->
  <form method="get" class="mb-3">
    <div class="row g-2">
      <div class="col-auto">
        <label for="dias" class="form-label">Período:</label>
        <select name="dias" id="dias" class="form-select" onchange="this.form.submit()">
          <option value="7" <?= $periodo === 7 ? 'selected' : '' ?>>Últimos 7 dias</option>
          <option value="15" <?= $periodo === 15 ? 'selected' : '' ?>>Últimos 15 dias</option>
          <option value="30" <?= $periodo === 30 ? 'selected' : '' ?>>Últimos 30 dias</option>
        </select>
      </div>
    </div>
  </form>

  <!-- 💡 Insights -->
  <div class="alert alert-info shadow-sm">
    <i class="bi bi-lightbulb"></i>
    Categoria mais lida: <strong><?= htmlspecialchars($categoriaMaisLida) ?></strong> —
    Média de livros lidos por dia: <strong><?= $mediaDiaria ?></strong>
  </div>

  <div class="row g-4 mb-4">
    <div class="col-md-12">
      <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
          <i class="bi bi-bar-chart-fill"></i> Atividades Diárias (<?= $periodo ?> dias)
        </div>
        <div class="card-body">
          <canvas id="graficoAtividades"></canvas>
        </div>
      </div>
    </div>

    <div class="col-md-12">
      <div class="card shadow-sm">
        <div class="card-header bg-dark text-white">
          <i class="bi bi-graph-up"></i> Evolução de Leituras
        </div>
        <div class="card-body">
          <canvas id="graficoLeituras"></canvas>
        </div>
      </div>
    </div>

    <div class="col-md-6">
      <div class="card shadow-sm">
        <div class="card-header bg-success text-white">
          <i class="bi bi-pie-chart"></i> Leituras por Categoria
        </div>
        <div class="card-body">
          <canvas id="graficoCategorias"></canvas>
        </div>
      </div>
    </div>

    <div class="col-md-6">
      <div class="card shadow-sm">
        <div class="card-header bg-secondary text-white">
          <i class="bi bi-people"></i> Leituras por Usuário
        </div>
        <div class="card-body">
          <canvas id="graficoUsuarios"></canvas>
        </div>
      </div>
    </div>

    <div class="col-md-6">
      <div class="card shadow-sm">
        <div class="card-header bg-warning text-white">
          <i class="bi bi-building"></i> Leituras por Editora
        </div>
        <div class="card-body">
          <canvas id="graficoEditoras"></canvas>
        </div>
      </div>
    </div>

    <div class="col-md-6">
      <div class="card shadow-sm">
        <div class="card-header bg-info text-white">
          <i class="bi bi-stars"></i> Top 5 Livros Mais Lidos
        </div>
        <div class="card-body">
          <ul class="list-group">
            <?php foreach ($maisLidos as $livro): ?>
              <li class="list-group-item d-flex justify-content-between align-items-center">
                <?= htmlspecialchars($livro['titulo']) ?>
                <span class="badge bg-primary rounded-pill"><?= $livro['total'] ?></span>
              </li>
            <?php endforeach; ?>
          </ul>
        </div>
      </div>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const datas = <?= json_encode($datas) ?>;
const livrosLidos = <?= json_encode($livrosLidos) ?>;
const favoritos = <?= json_encode($favoritos) ?>;
const comentarios = <?= json_encode($comentarios) ?>;
const modoEscuro = document.documentElement.getAttribute("data-tema") === 'escuro';

new Chart(document.getElementById("graficoAtividades"), {
  type: "bar",
  data: {
    labels: datas,
    datasets: [
      { label: "Livros Lidos", data: livrosLidos, backgroundColor: "#0d6efd" },
      { label: "Favoritos", data: favoritos, backgroundColor: "#ffc107" },
      { label: "Comentários", data: comentarios, backgroundColor: "#20c997" }
    ]
  },
  options: {
    responsive: true,
    plugins: { legend: { labels: { color: modoEscuro ? '#fff' : '#000' } } },
    scales: {
      x: { ticks: { color: modoEscuro ? '#fff' : '#000' } },
      y: { ticks: { color: modoEscuro ? '#fff' : '#000' }, beginAtZero: true }
    }
  }
});

new Chart(document.getElementById("graficoLeituras"), {
  type: "line",
  data: {
    labels: datas,
    datasets: [{ label: "Livros Lidos", data: livrosLidos, borderColor: "#0d6efd", fill: false, tension: 0.3 }]
  },
  options: {
    responsive: true,
    plugins: { legend: { labels: { color: modoEscuro ? '#fff' : '#000' } } },
    scales: {
      x: { ticks: { color: modoEscuro ? '#fff' : '#000' } },
      y: { ticks: { color: modoEscuro ? '#fff' : '#000' }, beginAtZero: true }
    }
  }
});
</script>