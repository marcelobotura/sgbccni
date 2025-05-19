<?php
require_once '../includes/config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_POST['acao'] === 'register') {
  $nome = trim($_POST['nome']);
  $email = trim($_POST['email']);
  $senha = $_POST['senha'];

  // Verifica se já existe o e-mail
  $stmt = $conn->prepare("SELECT id FROM usuarios WHERE email = ?");
  $stmt->bind_param("s", $email);
  $stmt->execute();
  $stmt->store_result();

  if ($stmt->num_rows > 0) {
    $_SESSION['erro'] = "Este e-mail já está cadastrado.";
    header("Location: ../public/register.php");
    exit;
  }

  // Cadastra novo usuário
  $senha_hash = password_hash($senha, PASSWORD_DEFAULT);
  $stmt = $conn->prepare("INSERT INTO usuarios (nome, email, senha) VALUES (?, ?, ?)");
  $stmt->bind_param("sss", $nome, $email, $senha_hash);

  if ($stmt->execute()) {
    $_SESSION['usuario_id'] = $stmt->insert_id;
    $_SESSION['usuario_nome'] = $nome;
    $_SESSION['usuario_tipo'] = 'usuario';
    header("Location: ../public/dashboard.php");
    exit;
  } else {
    $_SESSION['erro'] = "Erro ao cadastrar. Tente novamente.";
    header("Location: ../public/register.php");
    exit;
  }
}
