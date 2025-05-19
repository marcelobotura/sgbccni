<?php
define('URL_BASE', 'http://localhost/sgbccni/'); // Ajuste conforme sua pasta

session_start();

// Conexão com o banco de dados correto
$conn = new mysqli('localhost', 'root', '', 'sgbccni');

if ($conn->connect_error) {
  die("Erro na conexão: " . $conn->connect_error);
}
?>
