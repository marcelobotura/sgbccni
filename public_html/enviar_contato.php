<?php
define('BASE_PATH', __DIR__ . '/../app_backend');
require_once BASE_PATH . '/config/config.php';
require_once BASE_PATH . '/vendor/PHPMailer/PHPMailer.php';
require_once BASE_PATH . '/vendor/PHPMailer/SMTP.php';
require_once BASE_PATH . '/vendor/PHPMailer/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$nome = trim($_POST['nome'] ?? '');
$email = trim($_POST['email'] ?? '');
$mensagem = trim($_POST['mensagem'] ?? '');
$ip = $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';

if (!$nome || !$email || !$mensagem) {
    die('❌ Preencha todos os campos.');
}

// Bloqueio por IP (1 minuto entre envios)
$stmt = $conn->prepare("SELECT COUNT(*) FROM contatos WHERE ip = ? AND enviado_em > (NOW() - INTERVAL 1 MINUTE)");
$stmt->bind_param("s", $ip);
$stmt->execute();
$stmt->bind_result($tentativas);
$stmt->fetch();
$stmt->close();

if ($tentativas > 0) {
    die('⚠️ Aguarde 1 minuto antes de enviar outra mensagem.');
}

// Salvar no banco
$stmt = $conn->prepare("INSERT INTO contatos (nome, email, mensagem, ip) VALUES (?, ?, ?, ?)");
$stmt->bind_param("ssss", $nome, $email, $mensagem, $ip);
$stmt->execute();

// Enviar e-mail
$mail = new PHPMailer(true);
try {
    $mail->isSMTP();
    $mail->Host = 'smtp.hostinger.com';
    $mail->SMTPAuth = true;
    $mail->Username = getenv('EMAIL_SUPORTE');
    $mail->Password = 'SENHA_DO_EMAIL'; // Troque no .env
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = 587;

    $mail->setFrom(getenv('EMAIL_SUPORTE'), 'Biblioteca CNI');
    $mail->addAddress(getenv('EMAIL_SUPORTE'));
    $mail->addReplyTo($email, $nome); // permite resposta direta

    // Cópia ao usuário
    $mail->addCC($email);

    $mail->isHTML(true);
    $mail->Subject = '📩 Nova mensagem de contato';
    $mail->Body = "<strong>Nome:</strong> $nome<br><strong>Email:</strong> $email<br><br><strong>Mensagem:</strong><br>" . nl2br(htmlspecialchars($mensagem));

    $mail->send();
    echo '<h3>✅ Mensagem enviada e registrada com sucesso!</h3>';
} catch (Exception $e) {
    echo "❌ Erro ao enviar e-mail: {$mail->ErrorInfo}";
}
