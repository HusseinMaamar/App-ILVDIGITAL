<?php 
namespace models;
require 'vendor/autoload.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use Dotenv;

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load(); 

class Mailing 
{
   public function sendMail($email, $message, $subject, $headers) {
    try {
        $mail = new PHPMailer ;
        $mail->isSMTP();
        $mail->Host = $_ENV['SMTP_HOST'];
        $mail->Port = $_ENV['SMTP_PORT'];
        $mail->SMTPSecure = $_ENV['SMTP_SECURE'];
        $mail->SMTPAuth = filter_var($_ENV['SMTP_AUTH'], FILTER_VALIDATE_BOOLEAN);
        $mail->Username = $_ENV['SMTP_USERNAME'];
        $mail->Password = $_ENV['SMTP_PASSWORD'];
        $mail->setFrom($headers, 'ILV DIGITAL');
        $mail->addAddress($email, '');
        $mail->Subject = $subject ;
        $mail->isHTML(true);
        $mail->Body = <<<EOT
        <html>
        <head>
        <meta charset="UTF-8">
        </head>
        <body>
         <div style="margin: 0 auto; text-align: center; max-width: 600px;">
        <img src='https://app.ilvdigital.fr/public/assets/images/logoforemail.png' alt='ILV DIGITAL' style="margin-bottom: 20px; max-width:280px;">
        <p style="margin: 0; font-size: 16px; line-height: 1.5;">{$message}</p>
         </div>
         </body>
         </html>
    EOT;
        $mail->send(); 
        return true;

    } catch (Exception $e) {
        echo 'Erreur lors de l\'envoi de l\'e-mail : ', $mail->ErrorInfo;
        var_dump($e);
    }
   }
}



?>