<?php
// backend/config/config.php

// Carrega as variáveis do env.php
require_once __DIR__ . '/env.php';

// Define constantes globais do sistema
define('NOME_SISTEMA', 'Biblioteca Comunitária CNI');
define('URL_BASE', 'http://localhost/sgbccni/');

try {
    // Cria a conexão com o banco de dados usando PDO
    $conn = new PDO("mysql:host=$host;dbname=$db;charset=utf8", $user, $pass);

    // Define o modo de erro do PDO para exceção
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Define o modo padrão de fetch como FETCH_ASSOC
    $conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

    // Define fuso horário do MySQL
    $conn->exec("SET time_zone = '-03:00'");
} catch (PDOException $e) {
    // Erro amigável
    die("Erro na conexão com o banco de dados: " . $e->getMessage());
}
