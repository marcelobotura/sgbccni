<?php
session_start();
require_once __DIR__ . '/../../backend/config/config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $acao  = $_POST['acao'] ?? 'login';
  $email = trim($_POST['email'] ?? '');
  $senha = $_POST['senha'] ?? '';

  if ($acao === 'login') {
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

        if ($tipo === 'admin') {
          header("Location: ../../frontend/admin/dashboard.php");
        } else {
          header("Location: ../../frontend/usuario/index.php");
        }
        exit;
      } else {
        $_SESSION['erro'] = "Senha incorreta.";
      }
    } else {
      $_SESSION['erro'] = "E-mail não encontrado.";
    }

    $stmt->close();
    header("Location: login_admin.php");
    exit;
  }
} else {
  header("Location: login_admin.php");
  exit;
}
