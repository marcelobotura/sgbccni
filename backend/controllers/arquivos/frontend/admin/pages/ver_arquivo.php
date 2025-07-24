<?php
define('BASE_PATH', dirname(__DIR__, 3));
require_once BASE_PATH . '/backend/config/config.php';
require_once BASE_PATH . '/backend/includes/session.php';
require_once BASE_PATH . '/backend/includes/protect_admin.php';
require_once BASE_PATH . '/backend/includes/header.php';

exigir_login('admin');

$id = (int) ($_GET['id'] ?? 0);
$stmt = $pdo->prepare("SELECT * FROM arquivos WHERE id = ?");
$stmt->execute([$id]);
$arquivo = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$arquivo) {
    echo "<div class='alert alert-danger'>Arquivo não encontrado.</div>";
    require_once BASE_PATH . '/backend/includes/footer.php';
    exit;
}

// Caminho físico e URL
$caminho_rel = $arquivo['caminho'];
$caminho_abs = BASE_PATH . '/../' . $caminho_rel;
$url_arquivo = URL_BASE . $caminho_rel;
?>

<div class="container py-4">
  <h4 class="mb-3"><i class="bi bi-file-earmark-text"></i> Detalhes do Arquivo</h4>

  <table class="table table-bordered w-100">
    <tr>
      <th>ID</th>
      <td><?= $arquivo['id'] ?></td>
    </tr>
    <tr>
      <th>Nome</th>
      <td><?= htmlspecialchars($arquivo['nome']) ?></td>
    </tr>
    <tr>
      <th>Categoria</th>
      <td><?= ucfirst($arquivo['categoria']) ?></td>
    </tr>
    <tr>
      <th>Extensão</th>
      <td><?= strtoupper($arquivo['extensao']) ?></td>
    </tr>
    <tr>
      <th>Tamanho</th>
      <td><?= round($arquivo['tamanho'] / 1024, 1) ?> KB</td>
    </tr>
    <tr>
      <th>Caminho</th>
      <td><code><?= $arquivo['caminho'] ?></code></td>
    </tr>
    <tr>
      <th>Data de Envio</th>
      <td><?= date('d/m/Y H:i', strtotime($arquivo['data_envio'] ?? 'now')) ?></td>
    </tr>
    <tr>
      <th>Ações</th>
      <td>
        <a href="<?= $url_arquivo ?>" target="_blank" class="btn btn-sm btn-primary"><i class="bi bi-eye"></i> Ver</a>
        <a href="<?= $url_arquivo ?>" download class="btn btn-sm btn-success"><i class="bi bi-download"></i> Download</a>
        <a href="gerenciar_arquivos.php" class="btn btn-sm btn-secondary">← Voltar</a>
      </td>
    </tr>
  </table>
</div>

<?php require_once BASE_PATH . '/backend/includes/footer.php'; ?>
