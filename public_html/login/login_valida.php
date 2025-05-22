<?php
session_start();
require_once __DIR__ . '/../../config/config.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = trim($_POST['email']);
    $senha = $_POST['senha'];

    $stmt = $conn->prepare("SELECT id, nome, senha, tipo FROM usuarios WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows === 1) {
        $stmt->bind_result($id, $nome, $senha_hash, $tipo);
        $stmt->fetch();

        if (password_verify($senha, $senha_hash)) {
            session_regenerate_id(true); // ✅ Correção aplicada
            $_SESSION['usuario_id'] = $id;
            $_SESSION['usuario_nome'] = $nome;
            $_SESSION['usuario_tipo'] = $tipo;

            header("Location: ../" . ($tipo === 'admin' ? "admin" : "usuario") . "/index.php");
            exit;
        }
    }

    $_SESSION['erro'] = "E-mail ou senha inválidos.";
    header("Location: index.php");
    exit;
}
