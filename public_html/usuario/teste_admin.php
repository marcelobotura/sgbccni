<?php
session_start();
echo "<h3>🚀 Teste de Sessão Admin</h3>";

if (!isset($_SESSION['usuario_id'])) {
    echo "⚠️ Nenhuma sessão ativa.";
    exit;
}

echo "ID: " . $_SESSION['usuario_id'] . "<br>";
echo "Nome: " . $_SESSION['usuario_nome'] . "<br>";
echo "Tipo: " . $_SESSION['usuario_tipo'] . "<br>";

if ($_SESSION['usuario_tipo'] === 'admin') {
    echo "✅ Usuário é admin! Redirecionando...";
    header("refresh:2;url=admin/listar_livros.php");
    exit;
} else {
    echo "❌ Usuário não é admin!";
}
?>
