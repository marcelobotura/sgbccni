<?php
require_once __DIR__ . '/../../config/config.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require_once '../PHPMailer/PHPMailer.php';
require_once '../PHPMailer/SMTP.php';
require_once '../PHPMailer/Exception.php';

$email = $_POST['email'] ?? '';
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    die('E-mail inválido');
}

// Verifica se o e-mail está registrado
$stmt = $conn->prepare("SELECT id, nome FROM usuarios WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows !== 1) {
    die('E-mail não encontrado');
}

$usuario = $result->fetch_assoc();
$token = bin2hex(random_bytes(32));
$expira = date('Y-m-d H:i:s', strtotime('+1 hour'));

// Salva token no banco
$conn->query("DELETE FROM tokens_recuperacao WHERE usuario_id = {$usuario['id']}");
$stmt = $conn->prepare("INSERT INTO tokens_recuperacao (usuario_id, token, expira_em) VALUES (?, ?, ?)");
$stmt->bind_param("iss", $usuario['id'], $token, $expira);
$stmt->execute();

// Envia o e-mail
$link = URL_BASE . "login/redefinir_senha.php?token=$token";
$mail = new PHPMailer(true);
try {
    $mail->isSMTP();
    $mail->Host = 'smtp.hostinger.com';
    $mail->SMTPAuth = true;
    $mail->Username = getenv('EMAIL_SUPORTE');
    $mail->Password = 'SENHA_AQUI';
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = 587;

    $mail->setFrom(getenv('EMAIL_SUPORTE'), 'Biblioteca CNI');
    $mail->addAddress($email);

    $mail->isHTML(true);
    $mail->Subject = 'Recuperação de senha - Biblioteca CNI';
    $mail->Body    = "Olá {$usuario['nome']},<br>Para redefinir sua senha, clique no link abaixo:<br><a href='$link'>$link</a><br><br>Este link expira em 1 hora.";

    $mail->send();
    echo 'E-mail enviado com instruções.';
} catch (Exception $e) {
    echo "Erro ao enviar: {$mail->ErrorInfo}";
}
