<?php
require_once __DIR__ . '/../../../backend/config/config.php';
require_once __DIR__ . '/../../../backend/includes/protect_admin.php';
require_once __DIR__ . '/../../../backend/includes/header.php';

if ($_SESSION['usuario_tipo'] !== 'admin_master') {
    die('Acesso restrito ao administrador master.');
}

$id = $_GET['id'] ?? null;
if (!$id || !is_numeric($id)) {
    header("Location: emprestimos.php?erro=id_invalido");
    exit;
}

$stmt = $pdo->prepare("SELECT e.*, l.titulo, u.nome AS nome_usuario 
    FROM emprestimos e 
    JOIN livros l ON e.livro_id = l.id 
    JOIN usuarios u ON e.usuario_id = u.id 
    WHERE e.id = ?");
$stmt->execute([$id]);
$emprestimo = $stmt->fetch();

if (!$emprestimo) {
    header("Location: emprestimos.php?erro=nao_encontrado");
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <title>Editar Empréstimo - <?= NOME_SISTEMA ?></title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
</head>
<body class="container mt-5">
  <h3>✏️ Editar Empréstimo</h3>
  <p><strong>Livro:</strong> <?= htmlspecialchars($emprestimo['titulo']) ?></p>
  <p><strong>Usuário:</strong> <?= htmlspecialchars($emprestimo['nome_usuario']) ?></p>

  <form action="<?= URL_BASE ?>backend/controllers/emprestimos/salvar_edicao.php" method="post">
    <input type="hidden" name="id" value="<?= $emprestimo['id'] ?>">

    <div class="mb-3">
      <label for="data_prevista" class="form-label">Nova Data Prevista de Devolução</label>
      <input type="date" name="data_prevista_devolucao" class="form-control" required value="<?= $emprestimo['data_prevista_devolucao'] ?>">
    </div>

    <div class="mb-3">
      <label for="renovacoes" class="form-label">Renovações</label>
      <input type="number" name="renovacoes" class="form-control" min="0" value="<?= $emprestimo['renovacoes'] ?>">
    </div>

    <div class="mb-3">
      <label for="observacoes" class="form-label">Observações</label>
      <textarea name="observacoes" class="form-control"><?= htmlspecialchars($emprestimo['observacoes']) ?></textarea>
    </div>

    <div class="form-check mb-3">
      <input class="form-check-input" type="checkbox" name="zerar_multa" id="zerar_multa">
      <label class="form-check-label" for="zerar_multa">Zerar multa e atraso</label>
    </div>

    <button type="submit" class="btn btn-primary">Salvar Alterações</button>
    <a href="emprestimos.php" class="btn btn-secondary">Cancelar</a>
  </form>
</body>
</html>
