<?php
define('BASE_PATH', dirname(__DIR__, 2) . '/app_backend');
require_once BASE_PATH . '/config/config.php';
require_once BASE_PATH . '/includes/session.php';
include_once BASE_PATH . '/includes/header.php'; // Incluído para usar o Bootstrap e a função de mensagens

if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_tipo'] !== 'admin') {
    header("Location: ../login/index.php");
    exit;
}
?>

<div class="container py-4">
  <?php exibir_mensagens_sessao(); // Chamada para exibir as mensagens ?>
  <h2>🎛️ Painel do Admin: <?= htmlspecialchars($_SESSION['usuario_nome']) ?></h2>
  <p>Aqui você pode gerenciar livros, usuários e relatórios.</p>
  <div class="list-group">
    <a href="cadastrar_livro.php" class="list-group-item list-group-item-action">📘 Cadastrar Novo Livro</a>
    <a href="listar_livros.php" class="list-group-item list-group-item-action">📚 Listar Livros</a>
    </div>
  <div class="mt-4">
    <a href="../login/logout.php" class="btn btn-danger">Sair</a>
  </div>
</div>

<?php include_once BASE_PATH . '/includes/footer.php'; // Incluído para fechar o HTML e scripts ?>