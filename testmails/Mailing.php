<?php 
namespace models;
require 'vendor/autoload.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class Mailing 
{
   public function sendMail($email, $message, $subject, $headers) {
    try {
        $mail = new PHPMailer ;
        $mail->isSMTP();
        $mail->Host = 'smtp.ionos.fr';
        $mail->Port = 587;
        $mail->SMTPSecure = 'tls';
        $mail->SMTPAuth = true;
        $mail->Username = 'info@kiracom.fr';
        $mail->Password = 'IkillYouIfyouToucheMyWebSite\:)7';
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