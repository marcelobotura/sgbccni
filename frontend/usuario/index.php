<?php
session_start();

// Redireciona se não estiver logado como usuário
if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_tipo'] !== 'usuario') {
    header('Location: ../login/login_user.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <title>Painel do Usuário</title>
  <link rel="stylesheet" href="../assets/css/base.css">
  <link rel="stylesheet" href="../assets/css/componentes.css">
  <link rel="stylesheet" href="../assets/css/admin.css">
  <style>
    .painel {
      max-width: 1000px;
      margin: 2rem auto;
    }
    .header-painel {
      display: flex;
      justify-content: space-between;
      align-items: center;
    }
    .card-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
      gap: 1.5rem;
      margin-top: 2rem;
    }
  </style>
</head>
<body>
  <div class="painel">
    <div class="header-painel">
      <h2>Olá, <?php echo $_SESSION['usuario_nome']; ?> 👋</h2>
      <a href="/sgbccni/backend/controllers/auth/logout.php" class="btn btn-erro">Sair</a>
    </div>

    <div class="card-grid">
      <div class="card">
        <h3 class="card-title">Pesquisar Livros</h3>
        <p>Explore o acervo da biblioteca.</p>
        <a href="busca.php" class="btn btn-primario">Buscar</a>
      </div>

      <div class="card">
        <h3 class="card-title">Meus Empréstimos</h3>
        <p>Veja os livros que você retirou.</p>
        <a href="emprestimos.php" class="btn btn-primario">Visualizar</a>
      </div>

      <div class="card">
        <h3 class="card-title">Histórico</h3>
        <p>Confira os livros que já leu.</p>
        <a href="historico.php" class="btn btn-primario">Acessar</a>
      </div>

      <div class="card">
        <h3 class="card-title">Conta</h3>
        <p>Gerencie seus dados pessoais.</p>
        <a href="excluir_conta.php" class="btn btn-secundario">Excluir Conta</a>
      </div>
    </div>
  </div>
</body>
</html>
