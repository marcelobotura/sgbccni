<?php
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../includes/session.php';



header('Content-Type: application/json');

$email = trim($_POST['email'] ?? '');
$senha = trim($_POST['senha'] ?? '');

if (empty($email) || empty($senha)) {
    echo json_encode(['status' => 'erro', 'mensagem' => 'Preencha todos os campos.']);
    exit;
}

try {
    $stmt = $conn->prepare("SELECT id, nome, email, senha, tipo, foto FROM usuarios WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado->num_rows === 1) {
        $usuario = $resultado->fetch_assoc();

        if (password_verify($senha, $usuario['senha'])) {
            session_regenerate_id(true);
            $_SESSION['usuario_id'] = $usuario['id'];
            $_SESSION['usuario_nome'] = $usuario['nome'];
            $_SESSION['usuario_email'] = $usuario['email'];
            $_SESSION['usuario_tipo'] = $usuario['tipo'];
            $_SESSION['usuario_foto'] = $usuario['foto'] ?? null;

            echo json_encode([
                'status' => 'sucesso',
                'mensagem' => 'Login realizado com sucesso!',
                'tipo' => $usuario['tipo']
            ]);
            exit;
        }
    }

    echo json_encode(['status' => 'erro', 'mensagem' => 'E-mail ou senha incorretos.']);
} catch (Exception $e) {
    error_log("Erro no login: " . $e->getMessage());
    echo json_encode(['status' => 'erro', 'mensagem' => 'Erro interno. Tente novamente mais tarde.']);
}
exit;
