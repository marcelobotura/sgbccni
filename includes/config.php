<?php
define('URL_BASE', 'http://localhost/sgbccni/'); // Ajuste conforme sua pasta

// Inicia a sessão apenas se ainda não estiver ativa
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Conexão com o banco de dados
$conn = new mysqli('localhost', 'root', '', 'sgbccni');

if ($conn->connect_error) {
    die("Erro na conexão: " . $conn->connect_error);
}
?>


