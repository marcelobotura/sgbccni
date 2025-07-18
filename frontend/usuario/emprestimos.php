<?php
// 🔧 Exibir erros para desenvolvimento (remover em produção)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// 🔒 Includes e variáveis
define('BASE_PATH', dirname(__DIR__, 2) . '/backend');
require_once BASE_PATH . '/config/config.php';
require_once BASE_PATH . '/includes/session.php';
require_once BASE_PATH . '/includes/header.php';


exigir_login('usuario');

$usuario_id = $_SESSION['usuario_id'] ?? null;
$nome = htmlspecialchars($_SESSION['usuario_nome'] ?? 'Usuário');
$email = htmlspecialchars($_SESSION['usuario_email'] ?? '');

// 🔍 Consulta: livros lidos/devolvidos
$sql = "
SELECT 
    lu.data_leitura AS data_devolucao,
    lu.observacao,
    l.titulo,
    l.capa
FROM livros_usuarios lu
JOIN livros l ON lu.livro_id = l.id
WHERE lu.usuario_id = :usuario_id AND lu.status = 'devolvido'
ORDER BY lu.data_leitura DESC
";
$stmt = $pdo->prepare($sql);
$stmt->execute([':usuario_id' => $usuario_id]);
$historico = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <title>Histórico de Leitura - Biblioteca CNI</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- 🔠 Fontes e ícones -->
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
  <link rel="icon" type="image/png" href="<?= URL_BASE ?>assets/img/favicon.png">

  <!-- 🎨 Estilos -->
  <link rel="stylesheet" href="<?= URL_BASE ?>frontend/assets/css/base.css">
  <link rel="stylesheet" href="<?= URL_BASE ?>frontend/assets/css/layout.css">
  <link rel="stylesheet" href="<?= URL_BASE ?>frontend/assets/css/components.css">
  <link rel="stylesheet" href="<?= URL_BASE ?>frontend/assets/css/pages/painel_usuario.css">
  <link rel="stylesheet" href="<?= URL_BASE ?>frontend/assets/css/themes/light.css" id="theme-style">
</head>
<body>

<main class="painel-usuario container">
  <header class="painel-header d-flex justify-content-between align-items-center my-4">
    <div>
      <h2><i class="bi bi-clock-history"></i> Histórico de Leitura</h2>
    </div>
    <a href="index.php" class="btn btn-secundario"><i class="bi bi-arrow-left"></i> Voltar</a>
  </header>

  <?php if (empty($historico)): ?>
    <section class="card text-center">
      <i class="bi bi-info-circle text-info" style="font-size: 2rem;"></i>
      <p class="mt-3 mb-2 text-muted">Nenhum livro devolvido ainda.</p>
    </section>
  <?php else: ?>
    <div class="row g-4">
      <?php foreach ($historico as $livro): ?>
        <div class="col-md-6 col-lg-4">
          <div class="card h-100">
            <?php if (!empty($livro['capa'])): ?>
              <img src="<?= URL_BASE ?>uploads/capas/<?= htmlspecialchars($livro['capa']) ?>" class="img-fluid rounded mb-2" alt="Capa do livro">
            <?php endif; ?>

            <h5 class="card-title"><?= htmlspecialchars($livro['titulo']) ?></h5>

            <p class="mb-1 text-muted">
              ✅ Leitura finalizada em: <?= date('d/m/Y', strtotime($livro['data_devolucao'])) ?>
            </p>

            <?php if (!empty($livro['observacao'])): ?>
              <p class="small text-secondary mt-2">📝 <?= htmlspecialchars($livro['observacao']) ?></p>
            <?php endif; ?>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>
</main>

<?php require_once BASE_PATH . '/includes/footer.php'; ?>
</body>
</html>
