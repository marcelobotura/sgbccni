<?php
require_once __DIR__ . '/../../../config/config.php';
require_once BASE_PATH . '/backend/includes/verifica_admin.php';

if ($_SESSION['usuario_tipo'] !== 'admin_master') {
    die('Acesso não autorizado.');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'] ?? null;
    $data_prevista = $_POST['data_prevista_devolucao'] ?? null;
    $renovacoes = $_POST['renovacoes'] ?? 0;
    $observacoes = trim($_POST['observacoes'] ?? '');
    $zerar_multa = isset($_POST['zerar_multa']);

    if (!$id || !$data_prevista) {
        $_SESSION['erro'] = "Campos obrigatórios ausentes.";
        header("Location: " . URL_BASE . "frontend/admin/pages/emprestimos.php");
        exit;
    }

    try {
        $campos = [
            'data_prevista_devolucao' => $data_prevista,
            'renovacoes' => $renovacoes,
            'observacoes' => $observacoes
        ];

        if ($zerar_multa) {
            $campos['multa'] = 0.00;
            $campos['dias_atraso'] = 0;
        }

        $set = [];
        $params = [];
        foreach ($campos as $coluna => $valor) {
            $set[] = "$coluna = ?";
            $params[] = $valor;
        }
        $params[] = $id;

        $sql = "UPDATE emprestimos SET " . implode(', ', $set) . " WHERE id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);

        $_SESSION['sucesso'] = "Empréstimo atualizado com sucesso.";
    } catch (Exception $e) {
        $_SESSION['erro'] = "Erro ao atualizar: " . $e->getMessage();
    }

    header("Location: " . URL_BASE . "frontend/admin/pages/emprestimos.php");
    exit;
}
