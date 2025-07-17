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
    <title>Mapa do Sistema - Painel Admin</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="../../assets/css/pages/painel_admin.css">
</head>
<body>

<main class="painel-admin container">
    <div class="header">
        <h1>🗺️ Mapa do Sistema</h1>
    </div>

    <section class="content">
        <h2>📚 Funcionalidades</h2>
        <ul>
            <li>🏠 <strong>Início:</strong> Resumo geral e atalhos.</li>
            <li>👥 <strong>Usuários:</strong>
                <ul>
                    <li>➕ Cadastrar administrador</li>
                    <li>✏️ Editar usuário</li>
                    <li>🗑️ Excluir usuário</li>
                </ul>
            </li>
            <li>📚 <strong>Livros:</strong>
                <ul>
                    <li>📖 Listagem de livros</li>
                    <li>➕ Cadastrar livro</li>
                    <li>✏️ Editar livro</li>
                    <li>🗑️ Excluir livro</li>
                    <li>🔍 Visualizar livro (com QR Code)</li>
                </ul>
            </li>
            <li>🏷️ <strong>Tags:</strong> Gerenciar Autores, Editoras e Categorias.</li>
            <li>💬 <strong>Mensagens:</strong> Visualizar e excluir mensagens recebidas.</li>
            <li>⚙️ <strong>Configurações:</strong> Dados da biblioteca (nome, endereço, email, telefone, descrição, site).</li>
            <li>🗺️ <strong>Mapa do Sistema:</strong> (esta página)</li>
        </ul>

        <h2>🔐 Segurança</h2>
        <ul>
            <li>Login e Logout protegidos</li>
            <li>Verificação de sessão (admin/usuário)</li>
            <li>Controle de permissões</li>
        </ul>

        <h2>📄 Logs e Outros</h2>
        <ul>
            <li>Logs de redefinição de senha</li>
            <li>Logs de atividades (em desenvolvimento se desejar)</li>
        </ul>

        <br>
        <a href="../index.php" class="btn">⬅️ Voltar ao Painel</a>
    </section>
</main>

</body>
</html>
