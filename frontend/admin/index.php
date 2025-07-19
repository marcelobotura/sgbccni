<?php
// Caminho: frontend/admin/index.php
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
  <title>Painel do Administrador - Biblioteca CNI</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <!-- Bootstrap e Ícones -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">

  <!-- Estilo personalizado -->
  <link rel="stylesheet" href="../../assets/css/pages/painel_admin_moderno.css">

  <style>
    body { font-family: 'Inter', sans-serif; display: flex; }
    .sidebar {
      width: 240px;
      background-color: #0d6efd;
      color: white;
      height: 100vh;
      position: fixed;
      top: 0;
      left: 0;
      padding: 20px;
    }
    .sidebar a {
      display: block;
      color: white;
      text-decoration: none;
      padding: 10px 0;
    }
    .sidebar a:hover {
      background: rgba(255,255,255,0.1);
      border-radius: 5px;
    }
    .main-content {
      margin-left: 240px;
      padding: 30px;
      flex-grow: 1;
      background: #f8f9fa;
      min-height: 100vh;
    }
  </style>
</head>
<body>
  <!-- Sidebar -->
  <aside class="sidebar">
    <h4 class="mb-4">📚 Admin CNI</h4>
    <nav>
      <ul class="list-unstyled">
        <li><a href="#" data-page="dashboard"> <i class="bi bi-house-door"></i> Início</a></li>
        <li><a href="#" data-page="gerenciar_usuarios"> <i class="bi bi-people"></i> Usuários</a></li>
        <li><a href="#" data-page="gerenciar_livros"> <i class="bi bi-journal-text"></i> Livros</a></li>
        <li><a href="#" data-page="gerenciar_mensagens"> <i class="bi bi-chat-dots"></i> Mensagens</a></li>
        <li><a href="#" data-page="gerenciar_comentarios"> <i class="bi bi-chat-dots"></i> Comentarios</a></li>
        <li><a href="#" data-page="gerenciar_tags"> <i class="bi bi-tags"></i> Tags</a></li>
        <li><a href="#" data-page="configuracoes"> <i class="bi bi-gear"></i> Configurações</a></li>
        <li><a href="#" data-page="gerenciar_relatorios"> <i class="bi bi-bar-chart-line"></i> Relatórios</a></li>
        <li><a href="#" data-page="gerenciar_logs"> <i class="bi bi-clock-history"></i> Logs</a></li>
        <li><a href="#" data-page="backup"> <i class="bi bi-cloud-arrow-down"></i> Backup</a></li>
        <li><a href="#" data-page="restaurar_backup"> <i class="bi bi-cloud-arrow-up"></i> Restaurar</a></li>
        <li><a href="#" data-page="gerenciar_arquivos"> <i class="bi bi-folder"></i> Arquivos</a></li>
        <li><a href="#" data-page="mapa_sistema"> <i class="bi bi-diagram-3"></i> Mapa</a></li>
        <li><a href="../../backend/controllers/auth/logout.php"> <i class="bi bi-box-arrow-right"></i> Sair</a></li>
      </ul>
    </nav>
  </aside>

  <!-- Conteúdo dinâmico -->
  <main class="main-content">
    <div id="conteudo-principal">
      <h2>Bem-vindo ao Painel Admin!</h2>
      <p>Selecione um item no menu para começar.</p>
    </div>
  </main>

  <!-- JS Bootstrap + SPA Script -->
  <script>
    const links = document.querySelectorAll("[data-page]");
    const conteudo = document.getElementById("conteudo-principal");

    links.forEach(link => {
      link.addEventListener("click", function(e) {
        e.preventDefault();
        const page = this.dataset.page;

        fetch(`pages/${page}.php`)
          .then(res => {
            if (!res.ok) throw new Error("Erro ao carregar a página");
            return res.text();
          })
          .then(html => conteudo.innerHTML = html)
          .catch(err => conteudo.innerHTML = `<div class='alert alert-danger'>Erro: ${err.message}</div>`);
      });
    });
  </script>
</body>
</html>
