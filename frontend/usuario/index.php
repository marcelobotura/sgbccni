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
  <title>Painel do Usuário - Biblioteca CNI</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- CSS Modular -->
  <link rel="stylesheet" href="../assets/css/base.css">
  <link rel="stylesheet" href="../assets/css/layout.css">
  <link rel="stylesheet" href="../assets/css/components.css">
  <link rel="stylesheet" href="../assets/css/pages/painel_usuario.css">
  <link rel="stylesheet" href="../assets/css/themes/light.css" id="theme-style">
</head>
<body>

  <main class="painel-usuario container">
    <header class="painel-header d-flex justify-content-between align-items-center my-4">
      <h2>Olá, <?= htmlspecialchars($_SESSION['usuario_nome']); ?> 👋</h2>
      <a href="../../backend/controllers/auth/logout.php" class="btn btn-erro">Sair</a>


    </header>

    <section class="painel-cards grid gap-4">
      <article class="card">
        <h3 class="card-title">📚 Pesquisar Livros</h3>
        <p>Explore o acervo da biblioteca.</p>
        <a href="busca.php" class="btn btn-primario">Buscar</a>
      </article>

      <article class="card">
        <h3 class="card-title">📖 Meus Empréstimos</h3>
        <p>Acompanhe os livros emprestados.</p>
        <a href="emprestimos.php" class="btn btn-primario">Visualizar</a>
      </article>

      <article class="card">
        <h3 class="card-title">📅 Histórico</h3>
        <p>Veja os livros já lidos.</p>
        <a href="historico.php" class="btn btn-primario">Acessar</a>
      </article>

      <article class="card">
        <h3 class="card-title">⭐ Favoritos</h3>
        <p>Seus livros favoritos em um só lugar.</p>
        <a href="meus_favoritos.php" class="btn btn-primario">Ver Favoritos</a>
      </article>

      <article class="card">
        <h3 class="card-title">⚙️ Conta</h3>
        <p>Atualize seus dados de perfil.</p>
        <div class="d-flex flex-column gap-2 mt-2">
          <a href="editar_conta.php" class="btn btn-secundario">✏️ Editar Conta</a>
          <a href="excluir_conta.php" class="btn btn-erro">🗑️ Excluir Conta</a>
        </div>
      </article>
    </section>
  </main>

</body>
</html>
