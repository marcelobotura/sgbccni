<?php
include_once(__DIR__ . '/../config/config.php'); // ✅ novo caminho
include_once(__DIR__ . '/../includes/header.php');
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title><?= NOME_SISTEMA ?> - Início</title>

    <!-- Bootstrap e CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="<?= URL_BASE ?>assets/css/style.css" rel="stylesheet">
</head>
<body class="<?= $_COOKIE['modo_tema'] ?? 'dark' ?>">

    <div class="container mt-5">
       

        <?php include_once(__DIR__ . '/pesquisador.php'); ?>
    </div>

<?php include_once(__DIR__ . '/../includes/footer.php'); ?>
</body>
</html>
