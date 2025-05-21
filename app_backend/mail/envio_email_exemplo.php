<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require 'PHPMailer/PHPMailer.php';
require 'PHPMailer/SMTP.php';
require 'PHPMailer/Exception.php';

$mail = new PHPMailer(true);
try {
    $mail->isSMTP();
    $mail->Host = 'smtp.hostinger.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'seu_email@seudominio.com';
    $mail->Password = 'sua_senha';
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = 587;

    $mail->setFrom('seu_email@seudominio.com', 'Biblioteca CNI');
    $mail->addAddress('destinatario@exemplo.com');

    $mail->isHTML(true);
    $mail->Subject = 'Exemplo de e-mail';
    $mail->Body    = 'Este é um teste de envio via Hostinger SMTP.';

    $mail->send();
    echo 'Mensagem enviada com sucesso.';
} catch (Exception $e) {
    echo "Erro ao enviar: {$mail->ErrorInfo}";
}
