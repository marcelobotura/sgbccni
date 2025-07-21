<?php
// Caminho: backend/controllers/autenticacao/logout.php

session_start();
session_unset();      // Limpa as variáveis de sessão
session_destroy();    // Destrói a sessão

// Redireciona para a página de login
header("Location: /sgbccni/frontend/login/login.php");
exit;