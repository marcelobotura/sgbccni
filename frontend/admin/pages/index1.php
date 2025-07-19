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

    <!-- Ícones -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">

    <!-- Novo CSS -->
    <link rel="stylesheet" href="../../assets/css/pages/painel_admin_moderno.css">
</head>
<body>
<aside class="sidebar">
    <div class="logo">📚 <strong>Admin CNI</strong></div>
    <nav>
        <ul>
            <li><a href="index.php"><i class="bi bi-house-door"></i> Início</a></li>
            <li><a href="gerenciar_usuarios.php"><i class="bi bi-people"></i> Usuários</a></li>
            <li><a href="gerenciar_livros.php"><i class="bi bi-journal-text"></i> Livros</a></li>
            <li><a href="gerenciar_mensagens.php"><i class="bi bi-chat-dots"></i> Mensagens</a></li>
            <li><a href="gerenciar_tags.php"><i class="bi bi-tags"></i> Tags</a></li>
            <li><a href="configuracoes.php"><i class="bi bi-gear"></i> Configurações</a></li>
            <li><a href="gerenciar_relatorios.php"><i class="bi bi-bar-chart-line"></i> Relatórios</a></li>
            <li><a href="gerenciar_logs.php"><i class="bi bi-clock-history"></i> Logs</a></li>
            <li><a href="backup.php"><i class="bi bi-cloud-arrow-down"></i> Backup</a></li>
            <li><a href="restaurar_backup.php"><i class="bi bi-cloud-arrow-up"></i> Restaurar Backup</a></li>
            <li><a href="arquivos.php"><i class="bi bi-folder"></i> Arquivos</a></li>
            <li><a href="mapa_sistema.php"><i class="bi bi-diagram-3"></i> Mapa do Sistema</a></li>
            <li><a href="/sgbccni/backend/controllers/auth/logout.php"><i class="bi bi-box-arrow-right"></i> Sair</a></li>
        </ul>
    </nav>
</aside>

<main class="painel-admin">
    <div class="header">
        <h1>Olá, <?= htmlspecialchars($_SESSION['usuario_nome']); ?> 👋</h1>
        <p>Bem-vindo ao Painel Administrativo da Biblioteca CNI</p>
    </div>

    <section class="grid">
        <?php
        $cards = [
            ['icon' => 'bi-people', 'title' => 'Gerenciar Usuários', 'desc' => 'Controle os usuários cadastrados no sistema.', 'link' => 'gerenciar_usuarios.php'],
            ['icon' => 'bi-journal-text', 'title' => 'Gerenciar Livros', 'desc' => 'Adicione, edite ou remova livros da biblioteca.', 'link' => 'gerenciar_livros.php'],
            ['icon' => 'bi-chat-dots', 'title' => 'Mensagens', 'desc' => 'Visualize e responda mensagens dos usuários.', 'link' => 'gerenciar_mensagens.php'],
            ['icon' => 'bi-tags', 'title' => 'Tags', 'desc' => 'Gerencie autores, categorias e editoras.', 'link' => 'gerenciar_tags.php'],
            ['icon' => 'bi-gear', 'title' => 'Configurações', 'desc' => 'Configure informações da biblioteca e do sistema.', 'link' => 'configuracoes.php'],
            ['icon' => 'bi-bar-chart-line', 'title' => 'Relatórios', 'desc' => 'Veja estatísticas e relatórios do sistema.', 'link' => 'gerenciar_relatorios.php'],
            ['icon' => 'bi-clock-history', 'title' => 'Logs', 'desc' => 'Visualize atividades como login, exclusões e alterações.', 'link' => 'gerenciar_logs.php'],
            ['icon' => 'bi-cloud-arrow-down', 'title' => 'Backup', 'desc' => 'Baixe um backup completo do banco de dados.', 'link' => 'backup.php'],
            ['icon' => 'bi-cloud-arrow-up', 'title' => 'Restaurar Backup', 'desc' => 'Restaure um backup do banco de dados.', 'link' => 'restaurar_backup.php'],
            ['icon' => 'bi-folder', 'title' => 'Arquivos', 'desc' => 'Gerencie uploads como imagens de capas.', 'link' => 'arquivos.php'],
            ['icon' => 'bi-diagram-3', 'title' => 'Mapa do Sistema', 'desc' => 'Veja a estrutura e funcionalidades do sistema.', 'link' => 'mapa_sistema.php'],
        ];

        foreach ($cards as $card) {
            echo '<div class="card">
                    <h3><i class="bi ' . $card['icon'] . '"></i> ' . $card['title'] . '</h3>
                    <p>' . $card['desc'] . '</p>
                    <a href="' . $card['link'] . '" class="btn">Acessar</a>
                  </div>';
        }
        ?>
    </section>
</main>
</body>
</html>
