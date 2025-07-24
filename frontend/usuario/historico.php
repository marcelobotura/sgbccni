<?php
// 🔧 Exibir erros em desenvolvimento
ini_set('display_errors', 1);
error_reporting(E_ALL);

define('BASE_PATH', dirname(__DIR__, 2) . '/backend');
require_once BASE_PATH . '/config/config.php';
require_once BASE_PATH . '/includes/session.php';
require_once BASE_PATH . '/includes/header.php';
require_once __DIR__ . '/protect_usuario.php';

exigir_login('usuario');

$usuario_id = $_SESSION['usuario_id'] ?? null;
$nome = htmlspecialchars($_SESSION['usuario_nome'] ?? 'Usuário');

// 🔍 Consulta livros lidos/devolvidos
$sql = "
SELECT 
    l.id, l.titulo, l.capa_local, l.capa_url,
    lu.tipo_emprestimo, lu.data_emprestimo, lu.data_renovacao, lu.data_leitura AS data_devolucao,
    lu.observacao
FROM livros_usuarios lu
JOIN livros l ON lu.livro_id = l.id
WHERE lu.usuario_id = :usuario_id
  AND (lu.status = 'devolvido' OR lu.lido = 1)
ORDER BY lu.data_leitura DESC
";
$stmt = $pdo->prepare($sql);
$stmt->execute([':usuario_id' => $usuario_id]);
$historico = $stmt->fetchAll(PDO::FETCH_ASSOC);

// 🔁 Função para tratar capa
function capaLivro($livro) {
    if (!empty($livro['capa_local']) && file_exists(BASE_PATH . '/../storage/uploads/capas/' . $livro['capa_local'])) {
        return URL_BASE . 'storage/uploads/capas/' . $livro['capa_local'];
    }
    return !empty($livro['capa_url']) ? $livro['capa_url'] : URL_BASE . 'frontend/assets/img/livro_padrao.png';
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <title>Histórico de Leitura - <?= NOME_SISTEMA ?></title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
  <link rel="stylesheet" href="<?= URL_BASE ?>frontend/assets/css/base.css">
  <link rel="stylesheet" href="<?= URL_BASE ?>frontend/assets/css/layout.css">
  <link rel="stylesheet" href="<?= URL_BASE ?>frontend/assets/css/components.css">
  <link rel="stylesheet" href="<?= URL_BASE ?>frontend/assets/css/pages/painel_usuario.css">
</head>
<body>

<main class="painel-usuario container py-4">
  <header class="painel-header d-flex justify-content-between align-items-center mb-4">
    <h2><i class="bi bi-clock-history"></i> Histórico de Leitura</h2>
    <a href="index.php" class="btn btn-outline-secondary"><i class="bi bi-arrow-left"></i> Voltar</a>
  </header>

  <?php if (empty($historico)): ?>
    <div class="alert alert-info text-center shadow-sm">Nenhum livro lido ou devolvido ainda.</div>
  <?php else: ?>
    <div class="row row-cols-1 row-cols-md-2 g-4">
      <?php foreach ($historico as $livro): ?>
        <div class="col">
          <div class="card h-100 shadow-sm">
            <div class="row g-0">
              <div class="col-md-4">
                <img src="<?= capaLivro($livro) ?>" alt="Capa" class="img-fluid rounded-start h-100 object-fit-cover">
              </div>
              <div class="col-md-8">
                <div class="card-body d-flex flex-column h-100">
                  <h5 class="card-title"><?= htmlspecialchars($livro['titulo']) ?></h5>
                  <p class="card-text mb-1"><strong>Tipo de empréstimo:</strong>
                    <?= !empty($livro['tipo_emprestimo']) ? ucfirst($livro['tipo_emprestimo']) : '—' ?>
                  </p>
                  <p class="card-text mb-1"><strong>Data do empréstimo:</strong>
                    <?= !empty($livro['data_emprestimo']) ? date('d/m/Y', strtotime($livro['data_emprestimo'])) : '—' ?>
                  </p>
                  <p class="card-text mb-1"><strong>Data da renovação:</strong>
                    <?= !empty($livro['data_renovacao']) ? date('d/m/Y', strtotime($livro['data_renovacao'])) : '—' ?>
                  </p>
                  <p class="card-text mb-1"><strong>Data da devolução:</strong>
                    <?= !empty($livro['data_devolucao']) && $livro['data_devolucao'] !== '0000-00-00' ? date('d/m/Y', strtotime($livro['data_devolucao'])) : '—' ?>
                  </p>

                  <?php if (!empty($livro['observacao'])): ?>
                    <p class="card-text mt-2 text-muted small">📝 <?= htmlspecialchars($livro['observacao']) ?></p>
                  <?php endif; ?>

                  <a href="livro.php?id=<?= $livro['id'] ?>" class="btn btn-sm btn-primary mt-auto">
                    <i class="bi bi-eye"></i> Ver detalhes
                  </a>
                </div>
              </div>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>
</main>

<?php require_once BASE_PATH . '/includes/footer.php'; ?>
</body>
</html>
