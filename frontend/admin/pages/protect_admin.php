<?php
// Inicia a sessão se ainda não tiver sido iniciada
if (session_status() !== PHP_SESSION_ACTIVE) {
  session_start();
}

// Verifica se o usuário está logado e se é do tipo admin
if (
  !isset($_SESSION['usuario_id']) ||
  !isset($_SESSION['usuario_tipo']) ||
  $_SESSION['usuario_tipo'] !== 'admin'
) {
  // Redireciona para login de admin se não for admin autenticado
  header("Location: ../pages/login_admin.php");
  exit;
}
