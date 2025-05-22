<?php
session_start();
require_once __DIR__ . '/../../config/config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['acao'])) {
  $acao  = $_POST['acao'];
  $email = trim(filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL));
  $senha = $_POST['senha'];

  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $_SESSION['erro'] = "E-mail inválido.";
    header("Location: " . URL_BASE . "login/" . ($acao === 'register' ? "register.php" : "login.php"));
    exit;
  }

  // REGISTRO
  if ($acao === 'register') {
    $nome = trim(filter_input(INPUT_POST, 'nome', FILTER_SANITIZE_STRING));
    $tipo = 'usuario'; // padrão

    if (empty($nome) || empty($email) || empty($senha)) {
      $_SESSION['erro'] = "Preencha todos os campos.";
      header("Location: " . URL_BASE . "login/register.php");
      exit;
    }

    // Verifica se e-mail já existe
    $stmt = $conn->prepare("SELECT id FROM usuarios WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
      $_SESSION['erro'] = "Este e-mail já está cadastrado.";
      $stmt->close();
      header("Location: " . URL_BASE . "login/register.php");
      exit;
    }
    $stmt->close();

    // Cria novo usuário
    $senha_hash = password_hash($senha, PASSWORD_DEFAULT);
    $stmt = $conn->prepare("INSERT INTO usuarios (nome, email, senha, tipo) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $nome, $email, $senha_hash, $tipo);

    if ($stmt->execute()) {
      session_regenerate_id(true); // ✅ Segurança
      $_SESSION['usuario_id']   = $stmt->insert_id;
      $_SESSION['usuario_nome'] = htmlspecialchars($nome); // Protege exibição futura
      $_SESSION['usuario_tipo'] = $tipo;
      $stmt->close();

      header("Location: " . URL_BASE . "usuario/meus_livros.php");
      exit;
    } else {
      $_SESSION['erro'] = "Erro ao cadastrar.";
      header("Location: " . URL_BASE . "login/register.php");
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
        session_regenerate_id(true); // ✅ Segurança contra fixation
        $_SESSION['usuario_id']   = $id;
        $_SESSION['usuario_nome'] = htmlspecialchars($nome);
        $_SESSION['usuario_tipo'] = $tipo;
        $stmt->close();

        // Redireciona conforme o tipo
        header("Location: " . URL_BASE . ($tipo === 'admin' ? "admin/index.php" : "usuario/meus_livros.php"));
        exit;
      } else {
        $_SESSION['erro'] = "Senha incorreta.";
      }
    } else {
      $_SESSION['erro'] = "E-mail não encontrado.";
    }

    $stmt->close();
    header("Location: " . URL_BASE . "login/login.php");
    exit;
  }
}
