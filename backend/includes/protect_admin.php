<?php
// backend/includes/protect_admin.php
// Apenas carrega a sessão e exige login do tipo 'admin'
require_once __DIR__ . '/session.php'; // Garante que session.php e exigir_login estejam disponíveis 
exigir_login('admin'); // Chama a função para proteger a página 
?>