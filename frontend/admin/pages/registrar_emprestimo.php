<?php
require_once __DIR__ . '/../../../backend/config/config.php';
require_once __DIR__ . '/../../../backend/includes/protect_admin.php';
require_once __DIR__ . '/../../../backend/includes/header.php';

// 🔄 Buscar usuários e livros disponíveis
$usuarios = $pdo->query("SELECT id, nome FROM usuarios ORDER BY nome")->fetchAll();
$livros = $pdo->query("SELECT id, titulo FROM livros ORDER BY titulo")->fetchAll();
?>

<h2 class="mb-4">📚 Registrar Empréstimo</h2>

<form action="<?= URL_BASE ?>backend/controllers/emprestimos/registrar_emprestimo.php" method="POST">
  <div class="mb-3">
    <label for="usuario_id" class="form-label">Usuário:</label>
    <select name="usuario_id" id="usuario_id" class="form-select" required>
      <option value="">-- Selecione --</option>
      <?php foreach ($usuarios as $u): ?>
        <option value="<?= $u['id'] ?>"><?= htmlspecialchars($u['nome']) ?></option>
      <?php endforeach; ?>
    </select>
  </div>

  <div class="mb-3">
    <label for="livro_id" class="form-label">Livro:</label>
    <select name="livro_id" id="livro_id" class="form-select" required>
      <option value="">-- Selecione --</option>
      <?php foreach ($livros as $l): ?>
        <option value="<?= $l['id'] ?>"><?= htmlspecialchars($l['titulo']) ?></option>
      <?php endforeach; ?>
    </select>
  </div>

  <div class="mb-3">
    <label for="data_prevista" class="form-label">Data prevista para devolução:</label>
    <input type="date" name="data_prevista" id="data_prevista" class="form-control" required>
  </div>

  <div class="mb-3">
    <label for="observacao" class="form-label">Observações:</label>
    <textarea name="observacao" id="observacao" class="form-control" rows="2"></textarea>
  </div>

  <button type="submit" class="btn btn-primary">Registrar</button>
</form>
