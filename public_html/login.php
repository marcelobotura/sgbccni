<?php
session_start();

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <title>Login Provisório - Biblioteca CNI</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- Bootstrap + Google Fonts -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">

  <style>
    body {
      margin: 0;
      padding: 0;
      font-family: 'Inter', sans-serif;
      background: linear-gradient(135deg, #cfe2ff, #f8f9fa);
      height: 100vh;
      display: flex;
      justify-content: center;
      align-items: center;
      overflow: hidden;
    }

    .container-login {
      background: white;
      padding: 3rem;
      border-radius: 1rem;
      box-shadow: 0 15px 30px rgba(0, 0, 0, 0.1);
      text-align: center;
      animation: fadeInUp 0.8s ease-out forwards;
      transform: translateY(20px);
      opacity: 0;
    }

    @keyframes fadeInUp {
      to {
        transform: translateY(0);
        opacity: 1;
      }
    }

    .container-login h2 {
      font-weight: 600;
      margin-bottom: 1rem;
    }

    .container-login p {
      font-size: 0.95rem;
      color: #6c757d;
      margin-bottom: 2rem;
    }

    .btn-login {
      width: 180px;
      padding: 0.75rem;
      font-size: 1rem;
      margin: 0.5rem;
      border-radius: 8px;
      transition: transform 0.2s ease;
    }

    .btn-login:hover {
      transform: scale(1.05);
    }

    .footer-msg {
      font-size: 0.85rem;
      color: #888;
      margin-top: 2rem;
    }
  </style>
</head>
<body>
  <div class="container-login">
    <h2>🎓 Biblioteca CNI</h2>
    <p>Escolha abaixo como deseja acessar o sistema:</p>

    <div>
      <a href="../frontend/login/login_user.php" class="btn btn-primary btn-login">👤 Acesso Usuário</a>
      <a href="../frontend/login/login_admin.php" class="btn btn-dark btn-login">🛡️ Acesso Admin</a>
    </div>

    <div class="footer-msg">
      🔧 Esta tela de login é <strong>provisória</strong> e será substituída pela versão oficial em breve.
    </div>
  </div>
</body>
</html>
