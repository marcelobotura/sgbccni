<?php
define('BASE_PATH', dirname(__DIR__, 2));
require_once BASE_PATH . '/config/config.php';
require_once BASE_PATH . '/vendor/PHPMailer/PHPMailer.php';
require_once BASE_PATH . '/vendor/PHPMailer/SMTP.php';
require_once BASE_PATH . '/vendor/PHPMailer/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$email = $_POST['email'] ?? '';
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    die('E-mail inválido');
}

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

// Remove tokens antigos
$conn->query("DELETE FROM tokens_recuperacao WHERE usuario_id = {$usuario['id']}");

// Salva novo token
$stmt = $conn->prepare("INSERT INTO tokens_recuperacao (usuario_id, token, expira_em) VALUES (?, ?, ?)");
$stmt->bind_param("iss", $usuario['id'], $token, $expira);
$stmt->execute();

// Envia e-mail
$link = URL_BASE . "login/redefinir_senha.php?token=$token";

$mail = new PHPMailer(true);
try {
    $mail->isSMTP();
    $mail->Host = 'smtp.hostinger.com';
    $mail->SMTPAuth = true;
    $mail->Username = getenv('EMAIL_SUPORTE');
    $mail->Password = 'SENHA_DO_EMAIL'; // Configure no .env ou substitua aqui
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = 587;

    $mail->setFrom(getenv('EMAIL_SUPORTE'), 'Biblioteca CNI');
    $mail->addAddress($email);

    $mail->isHTML(true);
    $mail->Subject = '🔐 Recuperação de Senha - Biblioteca CNI';
    $mail->Body = "Olá <strong>{$usuario['nome']}</strong>,<br>Use o link abaixo para redefinir sua senha:<br><a href='{$link}'>{$link}</a><br><br>O link expira em 1 hora.";

    $mail->send();
    echo '✅ Um link de recuperação foi enviado para seu e-mail.';
} catch (Exception $e) {
    echo "❌ Erro ao enviar e-mail: {$mail->ErrorInfo}";
}
