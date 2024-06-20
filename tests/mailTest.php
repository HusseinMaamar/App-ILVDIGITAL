<?php 
require '../vendor/autoload.php';
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;
try{
$mail = new PHPMailer ;
$mail->isSMTP();
$mail->Host = 'smtp.ionos.fr';
$mail->Port = 587;
$mail->SMTPSecure = 'tls';
$mail->SMTPAuth = true;
$mail->Username = 'info@kiracom.fr';
$mail->Password = 'IkillYouIfyouToucheMyWebSite\:)7';
$mail->setFrom('info@kiracom.fr', 'ItPortfolio');
$mail->addAddress('hocinesadda14@gmail.com', '');
$mail->Subject = 'test' ;
$mail->isHTML(false);
$mail->Body = <<<EOT
Message: {'hello'}
EOT;
$mail->send(); 
/*      if ($mail->send()) {
    $msg = false;
} else {
$msg = true;
    } */

    if ($mail->send()) {
return true;
}
} catch (Exception $e) {
echo 'Erreur lors de l\'envoi de l\'e-mail : ', $mail->ErrorInfo;
var_dump($e);
}


?>