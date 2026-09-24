<?php

declare(strict_types=1);

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception as MailException;

$autoload = __DIR__ . '/../../vendor/autoload.php';
if (!is_file($autoload)) {
    throw new RuntimeException('Composer dependencies are missing. Run composer install.');
}
require_once $autoload;

function send_password_reset_email(array $config, string $recipient, string $recipientName, string $resetUrl): void
{
    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host = $config['smtp']['host'];
        $mail->SMTPAuth = true;
        $mail->Username = $config['smtp']['username'];
        $mail->Password = $config['smtp']['password'];
        $mail->Port = (int) $config['smtp']['port'];
        $mail->SMTPSecure = $config['smtp']['encryption'];
        $mail->setFrom($config['app']['mail_from'], $config['app']['mail_from_name']);
        $mail->addAddress($recipient, $recipientName);
        $mail->isHTML(true);
        $mail->Subject = 'Reset your Valcrest Meridian Capital password';
        $mail->Body = '<p>Hello ' . htmlspecialchars($recipientName, ENT_QUOTES, 'UTF-8') . ',</p><p>Use the button below to reset your password. This link expires in 60 minutes.</p><p><a href="' . htmlspecialchars($resetUrl, ENT_QUOTES, 'UTF-8') . '">Reset password</a></p><p>If you did not request this, you can ignore this email.</p>';
        $mail->AltBody = "Reset your password: {$resetUrl}\nThis link expires in 60 minutes.";
        $mail->send();
    } catch (MailException $exception) {
        throw new RuntimeException('Password reset email could not be sent.', 0, $exception);
    }
}
