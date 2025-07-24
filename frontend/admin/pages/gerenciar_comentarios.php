<?php
// Caminho: frontend/admin/pages/gerenciar_comentarios.php

define('BASE_PATH', dirname(__DIR__, 3));
require_once BASE_PATH . '/backend/config/config.php';
require_once BASE_PATH . '/backend/includes/session.php';
require_once BASE_PATH . '/backend/includes/protect_admin.php';
require_once BASE_PATH . '/backend/includes/header.php';


exigir_login('admin');

// Filtros
$filtro_aprovado = $_GET['status'] ?? '';
$busca_usuario = trim($_GET['usuario'] ?? '');
$pagina = max(1, intval($_GET['pagina'] ?? 1));
$por_pagina = 10;
$offset = ($pagina - 1) * $por_pagina;

// Consulta base
$sql = "SELECT c.*, u.nome AS nome_usuario, l.titulo AS titulo_livro
        FROM comentarios c
        JOIN usuarios u ON c.usuario_id = u.id
        JOIN livros l ON c.livro_id = l.id
        WHERE 1=1";
$params = [];

if ($filtro_aprovado !== '') {
    $sql .= " AND c.aprovado = ?";
    $params[] = $filtro_aprovado;
}

if (!empty($busca_usuario)) {
    $sql .= " AND u.nome LIKE ?";
    $params[] = "%$busca_usuario%";
}

$sql_total = $sql; // para contagem total
$sql .= " ORDER BY c.criado_em DESC LIMIT $por_pagina OFFSET $offset";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$comentarios = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Paginação
$stmt_total = $pdo->prepare($sql_total);
$stmt_total->execute($params);
$total_comentarios = $stmt_total->rowCount();
$total_paginas = ceil($total_comentarios / $por_pagina);
?>

<style>
  .linha-pendente {
    background-color: #fffce5;
  }
</style>

<div class="container py-4">
  <h2 class="mb-4"><i class="bi bi-chat-dots"></i> Gerenciar Comentários</h2>

  <!-- 🔍 Filtros -->
  <form method="GET" class="row g-2 mb-3">
    <div class="col-md-4">
      <input type="text" name="usuario" class="form-control" placeholder="Buscar por nome do usuário" value="<?= htmlspecialchars($busca_usuario) ?>">
    </div>
    <div class="col-md-3">
      <select name="status" class="form-select">
        <option value="">Todos</option>
        <option value="0" <?= $filtro_aprovado === '0' ? 'selected' : '' ?>>Pendentes</option>
        <option value="1" <?= $filtro_aprovado === '1' ? 'selected' : '' ?>>Aprovados</option>
      </select>
    </div>
    <div class="col-md-2">
      <button type="submit" class="btn btn-primary w-100"><i class="bi bi-search"></i> Filtrar</button>
    </div>
  </form>

  <?php if (count($comentarios) === 0): ?>
    <div class="alert alert-info">Nenhum comentário encontrado.</div>
  <?php else: ?>
    <div class="table-responsive">
      <table class="table table-hover align-middle">
        <thead>
          <tr>
            <th>Usuário</th>
            <th>Livro</th>
            <th>Comentário</th>
            <th>Data</th>
            <th>Status</th>
            <th>Ações</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($comentarios as $comentario): ?>
            <tr class="<?= !$comentario['aprovado'] ? 'linha-pendente' : '' ?>">
              <td><?= htmlspecialchars($comentario['nome_usuario']) ?></td>
              <td><?= htmlspecialchars($comentario['titulo_livro']) ?></td>
              <td>
                <button class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#modal<?= $comentario['id'] ?>">Ver</button>

                <!-- Modal -->
                <div class="modal fade" id="modal<?= $comentario['id'] ?>" tabindex="-1">
                  <div class="modal-dialog">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h5 class="modal-title">Comentário completo</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                      </div>
                      <div class="modal-body">
                        <?= nl2br(htmlspecialchars($comentario['texto'])) ?>
                      </div>
                    </div>
                  </div>
                </div>
              </td>
              <td><?= date('d/m/Y H:i', strtotime($comentario['criado_em'])) ?></td>
              <td>
                <?php if ($comentario['aprovado']): ?>
                  <span class="badge bg-success">Aprovado</span>
                <?php else: ?>
                  <span class="badge bg-warning text-dark">Pendente</span>
                <?php endif; ?>
              </td>
              <td>
                <a href="ver_livro.php?id=<?= $comentario['livro_id'] ?>" class="btn btn-sm btn-info" title="Ver livro"><i class="bi bi-book"></i></a>
                <?php if (!$comentario['aprovado']): ?>
                  <a href="<?= URL_BASE ?>backend/controllers/comentarios/aprovar_comentario.php?id=<?= $comentario['id'] ?>" class="btn btn-sm btn-success" title="Aprovar"><i class="bi bi-check"></i></a>
                <?php endif; ?>
                <a href="<?= URL_BASE ?>backend/controllers/comentarios/excluir_comentario.php?id=<?= $comentario['id'] ?>" class="btn btn-sm btn-danger" title="Excluir" onclick="return confirm('Excluir este comentário?');"><i class="bi bi-trash"></i></a>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>

    <!-- 🔄 Paginação -->
    <nav>
      <ul class="pagination">
        <?php for ($i = 1; $i <= $total_paginas; $i++): ?>
          <li class="page-item <?= $i == $pagina ? 'active' : '' ?>">
            <a class="page-link" href="?pagina=<?= $i ?>&status=<?= urlencode($filtro_aprovado) ?>&usuario=<?= urlencode($busca_usuario) ?>"><?= $i ?></a>
          </li>
        <?php endfor; ?>
      </ul>
    </nav>
  <?php endif; ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
