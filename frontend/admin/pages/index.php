<?php
session_start();

// Redireciona se não estiver logado como admin
if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_tipo'] !== 'admin') {
    header('Location: ../login/login_admin.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <title>Painel Administrativo</title>
  <link rel="stylesheet" href="../../assets/css/base.css">
  <link rel="stylesheet" href="../../assets/css/admin.css">
  <link rel="stylesheet" href="../../assets/css/componentes.css">
  <style>
    .painel {
      max-width: 1200px;
      margin: 2rem auto;
    }
    .header-painel {
      display: flex;
      justify-content: space-between;
      align-items: center;
    }
    .card-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
      gap: 1.5rem;
      margin-top: 2rem;
    }
  </style>
</head>
<body>
  <div class="painel">
    <div class="header-painel">
      <h2>Bem-vindo, <?php echo $_SESSION['usuario_nome']; ?> 👋</h2>
     <a href="../../../backend/controllers/auth/logout.php" class="btn btn-erro">Sair</a>


    </div>

    <div class="card-grid">
      <div class="card">
        <h3 class="card-title">Usuários</h3>
        <p>Gerencie os usuários cadastrados.</p>
        <a href="usuarios.php" class="btn btn-primario">Acessar</a>
      </div>

      <div class="card">
        <h3 class="card-title">Livros</h3>
        <p>Gerencie o acervo da biblioteca.</p>
        <a href="listar_livros.php" class="btn btn-primario">Acessar</a>
      </div>

      <div class="card">
        <h3 class="card-title">Mensagens</h3>
        <p>Consulte e modere mensagens.</p>
        <a href="mensagens.php" class="btn btn-primario">Acessar</a>
      </div>

      <div class="card">
        <h3 class="card-title">Tags</h3>
        <p>Organize categorias, autores e editoras.</p>
        <a href="gerenciar_tags.php" class="btn btn-primario">Acessar</a>
      </div>
    </div>
  </div>
</body>
</html>
