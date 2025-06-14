<?php
session_start();
if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_tipo'] !== 'admin') {
    header('Location: ../login/login_admin.php');
    exit;
}

require_once __DIR__ . '/../../../backend/config/env.php';

$msg = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_FILES['arquivo']) && $_FILES['arquivo']['error'] === UPLOAD_ERR_OK) {

        $arquivo_tmp = $_FILES['arquivo']['tmp_name'];
        $comando = "mysql --user=$user --password=$pass --host=$host $db < \"$arquivo_tmp\"";

        // 🔥 Caminho do mysql.exe (ajustar se necessário)
        $mysql = "C:\\xampp\\mysql\\bin\\mysql.exe";

        if (!file_exists($mysql)) {
            $msg = "<div class='alert alert-danger'>❌ mysql.exe não encontrado em <strong>$mysql</strong>. Verifique o caminho.</div>";
        } else {
            $comando = "\"$mysql\" --user=$user --password=$pass --host=$host $db < \"$arquivo_tmp\"";

            // Executa a restauração
            $resultado = shell_exec($comando);

            if ($resultado === null) {
                $msg = "<div class='alert alert-success'>✅ Backup restaurado com sucesso!</div>";
            } else {
                $msg = "<div class='alert alert-danger'>❌ Erro ao restaurar backup: <pre>$resultado</pre></div>";
            }
        }
    } else {
        $msg = "<div class='alert alert-danger'>❌ Erro no upload do arquivo.</div>";
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Restaurar Backup</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="stylesheet" href="../../assets/css/pages/painel_admin.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
</head>
<body>

<aside class="sidebar">
    <div class="logo">📚 Admin CNI</div>
    <nav>
        <ul>
            <li><a href="index.php"><i class="bi bi-house-door"></i> Início</a></li>
            <li><a href="backup.php"><i class="bi bi-cloud-download"></i> Backup</a></li>
            <li><a href="restaurar_backup.php" class="active"><i class="bi bi-upload"></i> Restaurar Backup</a></li>
            <li><a href="../../../backend/controllers/auth/logout.php"><i class="bi bi-box-arrow-right"></i> Sair</a></li>
        </ul>
    </nav>
</aside>

<main class="painel-admin">
    <div class="header">
        <h1>🗄️ Restaurar Backup</h1>
    </div>

    <?= $msg ?>

    <form method="POST" enctype="multipart/form-data" class="form-card">
        <div class="mb-3">
            <label class="form-label">Selecione o arquivo .SQL</label>
            <input type="file" name="arquivo" accept=".sql" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primario">
            <i class="bi bi-upload"></i> Restaurar Backup
        </button>
    </form>
</main>

</body>
</html>
