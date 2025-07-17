<?php
function registrar_atividade(PDO $pdo, string $usuario, string $acao): void {
    try {
        $stmt = $pdo->prepare("INSERT INTO log_atividade (usuario, acao, data_atividade) VALUES (:usuario, :acao, NOW())");
        $stmt->execute([
            ':usuario' => $usuario,
            ':acao'    => $acao
        ]);
    } catch (PDOException $e) {
        // Futuro: gravar erro em log local
    }
}
