<?php
session_start();
if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_tipo'] !== 'admin') {
    header('Location: ../login/login_admin.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <title>Painel do Administrador</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- Bootstrap Icons -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">

  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">

  <!-- CSS externo -->
  
  <link rel="stylesheet" href="../../assets/css/pages/painel_admin.css">



</head>
<body>
  <aside class="sidebar">
    <div class="logo">📚 Admin CNI</div>
    <nav>
      <ul>
        <li><a href="index.php"><i class="bi bi-house-door"></i> Início</a></li>
        <li><a href="usuarios.php"><i class="bi bi-people"></i> Usuários</a></li>
        <li><a href="listar_livros.php"><i class="bi bi-journal-text"></i> Livros</a></li>
        <li><a href="mensagens.php"><i class="bi bi-chat-dots"></i> Mensagens</a></li>
        <li><a href="gerenciar_tags.php"><i class="bi bi-tags"></i> Tags</a></li>
        <li><a href="configuracoes.php"><i class="bi bi-gear"></i> Configurações</a></li>
        <li><a href="../../../backend/controllers/auth/logout.php"><i class="bi bi-box-arrow-right"></i> Sair</a></li>
      </ul>
    </nav>
  </aside>
  <main class="painel-admin">
    <div class="header">
      <h1>Bem-vindo, <?= htmlspecialchars($_SESSION['usuario_nome']); ?> 👋</h1>
    </div>
    <section class="grid">
      <div class="card">
        <h3><i class="bi bi-people"></i> Gerenciar Usuários</h3>
        <p>Controle as contas cadastradas no sistema.</p>
        <a href="usuarios.php" class="btn">Acessar</a>
      </div>
      <div class="card">
        <h3><i class="bi bi-journal-text"></i> Gerenciar Livros</h3>
        <p>Adicione e atualize o acervo da biblioteca.</p>
        <a href="listar_livros.php" class="btn">Ver Livros</a>
      </div>
      <div class="card">
        <h3><i class="bi bi-chat-dots"></i> Mensagens</h3>
        <p>Visualize e modere os comentários dos usuários.</p>
        <a href="mensagens.php" class="btn">Moderador</a>
      </div>
      <div class="card">
        <h3><i class="bi bi-tags"></i> Tags</h3>
        <p>Organize categorias, autores e editoras.</p>
        <a href="gerenciar_tags.php" class="btn">Editar</a>
      </div>
      <div class="card">
        <h3><i class="bi bi-gear"></i> Configurações</h3>
        <p>Configure os parâmetros do sistema.</p>
        <a href="configuracoes.php" class="btn">Ajustar</a>
      </div>
    </section>
  </main>
</body>
</html>
