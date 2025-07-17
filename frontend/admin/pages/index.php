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
    <title>Painel do Administrador - Biblioteca CNI</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">

    <!-- CSS do Painel -->
    <link rel="stylesheet" href="../../assets/css/pages/painel_admin.css">
</head>
<body>

<!-- 🔷 Sidebar -->
<aside class="sidebar">
    <div class="logo">📚 Admin CNI</div>
    <nav>
        <ul>
            <li><a href="index.php"><i class="bi bi-house-door"></i> Início</a></li>
            <li><a href="gerenciar_usuarios.php"><i class="bi bi-people"></i> Usuários</a></li> 
            <li><a href="gerenciar_livros.php"><i class="bi bi-journal-text"></i> Livros</a></li>
            <li><a href="mensagens.php"><i class="bi bi-chat-dots"></i> Mensagens</a></li>
            <li><a href="gerenciar_tags.php"><i class="bi bi-tags"></i> Tags</a></li>
            <li><a href="configuracoes.php"><i class="bi bi-gear"></i> Configurações</a></li>
            <li><a href="gerenciar_relatorios.php"><i class="bi bi-bar-chart-line"></i> Relatórios</a></li>
            <li><a href="gerenciar_logs.php"><i class="bi bi-clock-history"></i> Logs</a></li>
            <li><a href="backup.php"><i class="bi bi-cloud-arrow-down"></i> Backup</a></li>
            <li><a href="restaurar_backup.php"><i class="bi bi-cloud-arrow-up"></i> Restaurar Backup</a></li>
            <li><a href="arquivos.php"><i class="bi bi-folder"></i> Arquivos</a></li>
            <li><a href="mapa_sistema.php"><i class="bi bi-diagram-3"></i> Mapa do Sistema</a></li>
            <a href="/sgbccni/backend/controllers/auth/logout.php">
            <i class="bi bi-box-arrow-right"></i> Sair
            </a>
        </ul>
    </nav>
</aside>

<!-- 🔸 Conteúdo Principal -->
<main class="painel-admin">
    <div class="header">
        <h1>Bem-vindo, <?= htmlspecialchars($_SESSION['usuario_nome']); ?> 👋</h1>
    </div>

    <section class="grid">

        <div class="card">
            <h3><i class="bi bi-people"></i> Gerenciar Usuários</h3>
            <p>Controle os usuários cadastrados no sistema.</p>
            <a href="gerenciar_usuarios.php" class="btn">Acessar</a>
        </div>

        <div class="card">
            <h3><i class="bi bi-journal-text"></i> Gerenciar Livros</h3>
            <p>Adicione, edite ou remova livros da biblioteca.</p>
            <a href="gerenciar_livros.php" class="btn">Ver Livros</a>
        </div>

        <div class="card">
            <h3><i class="bi bi-chat-dots"></i> Mensagens</h3>
            <p>Visualize e responda mensagens dos usuários.</p>
            <a href="gerenciar_mensagens.php" class="btn">Moderador</a>
        </div>

        <div class="card">
            <h3><i class="bi bi-tags"></i> Tags</h3>
            <p>Gerencie autores, categorias e editoras.</p>
            <a href="gerenciar_tags.php" class="btn">Editar</a>
        </div>

        <div class="card">
            <h3><i class="bi bi-gear"></i> Configurações</h3>
            <p>Configure informações da biblioteca e do sistema.</p>
            <a href="configuracoes.php" class="btn">Ajustar</a>
        </div>

        <div class="card">
            <h3><i class="bi bi-bar-chart-line"></i> Relatórios</h3>
            <p>Veja estatísticas e relatórios do sistema.</p>
            <a href="gerenciar_relatorios.php" class="btn">Acessar</a>
        </div>

        <div class="card">
            <h3><i class="bi bi-clock-history"></i> Logs</h3>
            <p>Visualize atividades como login, exclusões e alterações.</p>
            <a href="gerenciar_logs.php" class="btn">Ver Logs</a>
        </div>

        <div class="card">
            <h3><i class="bi bi-cloud-arrow-down"></i> Backup</h3>
            <p>Baixe um backup completo do banco de dados.</p>
            <a href="backup.php" class="btn">Fazer Backup</a>
        </div>

        <div class="card">
            <h3><i class="bi bi-cloud-arrow-up"></i> Restaurar Backup</h3>
            <p>Restaure um backup do banco de dados.</p>
            <a href="restaurar_backup.php" class="btn">Restaurar Backup</a>
        </div>

        <div class="card">
            <h3><i class="bi bi-folder"></i> Gerenciador de Arquivos</h3>
            <p>Gerencie uploads como imagens de capas.</p>
            <a href="gerenciar_arquivos.php" class="btn">Arquivos</a>
        </div>

        <div class="card">
            <h3><i class="bi bi-diagram-3"></i> Mapa do Sistema</h3>
            <p>Veja a estrutura e as funcionalidades do sistema.</p>
            <a href="mapa_sistema.php" class="btn">Visualizar</a>
        </div>

    </section>
</main>

</body>
</html>
