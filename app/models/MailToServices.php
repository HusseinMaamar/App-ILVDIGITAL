<?php
namespace models;
require 'vendor/autoload.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use Dotenv;

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load(); 


class MailToServices 
{
    public function sendMail($email, $message, $subject, $headers, $attachments = []) {
        try {
            $mail = new PHPMailer;
            $mail->isSMTP();
            $mail->Host = $_ENV['SMTP_HOST'];
            $mail->Port = $_ENV['SMTP_PORT'];
            $mail->SMTPSecure = $_ENV['SMTP_SECURE'];
            $mail->SMTPAuth = filter_var($_ENV['SMTP_AUTH'], FILTER_VALIDATE_BOOLEAN);
            $mail->Username = $_ENV['SMTP_USERNAME'];
            $mail->Password = $_ENV['SMTP_PASSWORD'];
            $mail->setFrom($headers, 'ILV DIGITAL');
            $mail->addAddress($email, '');

            $mail->Subject = $subject;
            $mail->isHTML(true);
            $mail->Body = <<<EOT
                <html>
                    <head>
                        <meta charset="UTF-8">
                    </head>
                    <body>
                        <div style="margin: 0 auto; text-align: center; max-width: 600px;">
                            <p style="margin: 0; font-size: 16px; line-height: 1.5;">{$message}</p>
                            <span style="margin:15px; font-size: 12px; color:#aaa;">By ILVDIGITAL.FR </span>
                        </div>
                    </body>
                </html>
            EOT;

            // Ajout des pièces jointes
            foreach ($attachments as $attachment) {
                $mail->addAttachment($attachment['path'], $attachment['name']);
            }

            $mail->send();
            return true;
        } catch (Exception $e) {
            echo 'Erreur lors de l\'envoi de l\'e-mail : ', $mail->ErrorInfo;
            var_dump($e);
            return false;
        }
    }
}
