<?php
// backend/config/config.php

// Carrega as variáveis do env.php
require_once 'env.php';

try {
    // Cria a conexão com o banco de dados usando PDO
    $conn = new PDO("mysql:host=$host;dbname=$db;charset=utf8", $user, $pass);
    
    // Define o modo de erro do PDO para exceção
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Opcional: define o fuso horário do MySQL
    $conn->exec("SET time_zone = '-03:00'");
} catch (PDOException $e) {
    // Exibe erro amigável se a conexão falhar
    die("Erro na conexão com o banco de dados: " . $e->getMessage());
}
