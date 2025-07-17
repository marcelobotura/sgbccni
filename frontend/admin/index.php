<?php
session_start();

// Verifica se o admin está logado
if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_tipo'] !== 'admin') {
    header('Location: ../login/login_admin.php');
    exit;
}

// Define constante da base da URL (caso não definida)
if (!defined('URL_BASE')) {
    define('URL_BASE', '/sgbccni/');
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <title>Bem-vindo ao Painel Admin - Biblioteca CNI</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- Google Fonts + Bootstrap Icons -->
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

  <style>
    body {
      margin: 0;
      padding: 0;
      font-family: 'Inter', sans-serif;
      background: #f4f4f4;
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      height: 100vh;
      text-align: center;
    }

    .painel {
      background: white;
      border-radius: 16px;
      padding: 40px;
      box-shadow: 0 4px 12px rgba(0,0,0,0.1);
      max-width: 500px;
      width: 90%;
    }

    .painel h1 {
      font-size: 28px;
      margin-bottom: 10px;
      color: #333;
    }

    .painel p {
      font-size: 16px;
      color: #666;
      margin-bottom: 30px;
    }

    .btn {
      display: inline-flex;
      align-items: center;
      justify-content: center;
      padding: 12px 20px;
      margin: 8px;
      font-size: 16px;
      text-decoration: none;
      border-radius: 8px;
      transition: background 0.3s ease;
    }

    .btn-primary {
      background: #007bff;
      color: white;
    }

    .btn-primary:hover {
      background: #0056b3;
    }

    .btn-outline {
      background: transparent;
      border: 2px solid #dc3545;
      color: #dc3545;
    }

    .btn-outline:hover {
      background: #dc3545;
      color: white;
    }

    .icon {
      margin-right: 8px;
    }

    .logo {
      font-size: 36px;
      margin-bottom: 20px;
    }
  </style>
</head>
<body>

  <div class="painel">
    <div class="logo">📚 Biblioteca CNI</div>

    <h1>Olá, <?= htmlspecialchars($_SESSION['usuario_nome']); ?> 👋</h1>
    <p>Seja bem-vindo(a) ao painel administrativo.</p>

    <a href="pages/index.php" class="btn btn-primary">
      <i class="bi bi-grid icon"></i> Ir para o Painel Completo
    </a>

    <a href="<?= URL_BASE ?>backend/controllers/auth/logout.php" class="btn btn-outline">
      <i class="bi bi-box-arrow-right icon"></i> Sair
    </a>
  </div>

</body>
</html>
