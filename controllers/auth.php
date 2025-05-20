<?php
session_start();
require_once '../config/config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['acao'])) {
  $acao  = $_POST['acao'];
  $email = trim($_POST['email']);
  $senha = $_POST['senha'];

  // REGISTRO
  if ($acao === 'register') {
    $nome = trim($_POST['nome']);
    $tipo = 'usuario'; // padrão

    if (empty($nome) || empty($email) || empty($senha)) {
      $_SESSION['erro'] = "Preencha todos os campos.";
      header("Location: ../login/register.php");
      exit;
    }

    $stmt = $conn->prepare("SELECT id FROM usuarios WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
      $_SESSION['erro'] = "Este e-mail já está cadastrado.";
      $stmt->close();
      header("Location: ../login/register.php");
      exit;
    }

    $stmt->close();

    $senha_hash = password_hash($senha, PASSWORD_DEFAULT);
    $stmt = $conn->prepare("INSERT INTO usuarios (nome, email, senha, tipo) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $nome, $email, $senha_hash, $tipo);

    if ($stmt->execute()) {
      $_SESSION['usuario_id'] = $stmt->insert_id;
      $_SESSION['usuario_nome'] = $nome;
      $_SESSION['usuario_tipo'] = $tipo;
      $stmt->close();
      header("Location: ../public/meus_livros.php");
      exit;
    } else {
      $_SESSION['erro'] = "Erro ao cadastrar.";
      header("Location: ../login/register.php");
      exit;
    }

  }

  // LOGIN
  elseif ($acao === 'login') {
    $stmt = $conn->prepare("SELECT id, nome, senha, tipo FROM usuarios WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows === 1) {
      $stmt->bind_result($id, $nome, $senha_hash, $tipo);
      $stmt->fetch();

      if (password_verify($senha, $senha_hash)) {
        $_SESSION['usuario_id'] = $id;
        $_SESSION['usuario_nome'] = $nome;
        $_SESSION['usuario_tipo'] = $tipo;
        $stmt->close();

        // Redireciona conforme tipo
        if ($tipo === 'admin') {
          header("Location: ../admin/pages/index.php");
        } else {
          header("Location: ../public/meus_livros.php");
        }
        exit;
      } else {
        $_SESSION['erro'] = "Senha incorreta.";
      }
    } else {
      $_SESSION['erro'] = "E-mail não encontrado.";
    }

    $stmt->close();
    header("Location: ../login/login.php");
    exit;
  }
}
