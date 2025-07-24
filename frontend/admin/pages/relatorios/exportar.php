<?php
// Caminho: frontend/admin/pages/relatorios/exportar.php

define('BASE_PATH', dirname(__DIR__, 4));
require_once BASE_PATH . '/backend/config/config.php';
require_once BASE_PATH . '/backend/includes/session.php';
require_once BASE_PATH . '/backend/includes/protect_admin.php';
require_once BASE_PATH . '/backend/includes/header.php';
require_once BASE_PATH . '/backend/includes/menu.php';

exigir_login('admin');

$relatorios = [
  'livros_lidos' => 'Livros Lidos',
  'mais_lidos'   => 'Mais Lidos',
  'favoritos'    => 'Favoritos',
  'categorias'   => 'Por Categoria',
  'editoras'     => 'Por Editora'
];
?>

<div class="container py-4">
  <h2 class="mb-4"><i class="bi bi-download"></i> Exportar Relatórios Individuais</h2>
  <p class="lead">Escolha o tipo de relatório e o formato de exportação.</p>

  <div class="table-responsive">
    <table class="table table-bordered align-middle text-center">
      <thead class="table-light">
        <tr>
          <th>Relatório</th>
          <th>Exportar em PDF</th>
          <th>Exportar em Excel (CSV)</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($relatorios as $tipo => $titulo): ?>
        <tr>
          <td class="text-start"><?= htmlspecialchars($titulo) ?></td>
          <td>
            <a href="<?= URL_BASE ?>backend/controllers/relatorios/gerar_pdf.php?relatorio=<?= $tipo ?>" class="btn btn-danger btn-sm">
              <i class="bi bi-filetype-pdf"></i> PDF
            </a>
          </td>
          <td>
            <a href="<?= URL_BASE ?>backend/controllers/relatorios/gerar_excel.php?relatorio=<?= $tipo ?>" class="btn btn-success btn-sm">
              <i class="bi bi-file-earmark-spreadsheet"></i> Excel
            </a>
          </td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>
