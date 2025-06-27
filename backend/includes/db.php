<?php
require_once __DIR__ . '/../config/env.php';
try {
    $conn = new PDO("mysql:host=$host;dbname=$db;charset=utf8", $user, $pass);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die('<div class="alert alert-danger">Erro ao conectar ao banco: ' . $e->getMessage() . '</div>');
}
