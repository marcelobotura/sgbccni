<?php
define('BASE_PATH', dirname(__DIR__, 1));
require_once BASE_PATH . '/config/config.php';
require_once BASE_PATH . '/vendor/PHPMailer/PHPMailer.php';
require_once BASE_PATH . '/vendor/PHPMailer/SMTP.php';
require_once BASE_PATH . '/vendor/PHPMailer/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$mail = new PHPMailer(true);
try {
    $mail->isSMTP();
    $mail->Host = 'smtp.hostinger.com';
    $mail->SMTPAuth = true;
    $mail->Username = getenv('EMAIL_SUPORTE');
    $mail->Password = 'SENHA_DO_EMAIL';
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = 587;

    $mail->setFrom(getenv('EMAIL_SUPORTE'), 'Biblioteca CNI');
    $mail->addAddress('mbsfoz@gmail.com');

    $mail->isHTML(true);
    $mail->Subject = '📨 Teste de envio de e-mail';
    $mail->Body = 'Este é um <strong>teste</strong> de envio via PHPMailer com Hostinger.';

    $mail->send();
    echo '✅ E-mail enviado com sucesso.';
} catch (Exception $e) {
    echo "❌ Erro ao enviar e-mail: {$mail->ErrorInfo}";
}
