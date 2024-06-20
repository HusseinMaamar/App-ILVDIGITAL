<?php 
//class plates
namespace controller;
use models\PlatesModel;
use models\CategoryPlatesModel;
use helpers\ViewRenderer;
use models\Mailing;
use models\MailToServices;
use services\GoogleReviewsLinkGenerator; 


class PlatesController
{
    
   private $PlatesModel;
   private $CategoryPlatesModel;
   private $view;
   private $mail;
   private $mailServices ;
   //private $generatorLinkReviws; 

   public function __construct()
   {
      $this->PlatesModel = new PlatesModel;
      $this->CategoryPlatesModel = new CategoryPlatesModel;
      $this->view = new ViewRenderer;
      $this->mail = new Mailing;
      $this->mailServices = new MailToServices ;

   }  /// construct model and view


public function qrCode($token)
{   
    $_SESSION['getToken'] = $token;

    $resultatCheckPlaque  = $this->checkPlaque($token);
    
    if (!empty($resultatCheckPlaque) && $resultatCheckPlaque[1] == 1) {
        if($resultatCheckPlaque[2] == 5){
        header("Location: mailTo/$token");
        exit();  
        }else{
         header("Location: $resultatCheckPlaque[0]");
         exit();    
        }
    }else{
        header("Location:join");
        exit();
    }

}

public function mailTo($token)
{   
    $alert ='';
    $alertValid ='';
    $resultatCheckPlaque  = $this->checkPlaque($token);
    
    if($_POST){
    
        $email = $resultatCheckPlaque[0];
        $message = $_POST['message'];
        $subject = $_POST['subject'];
        
        // Traitement des pièces jointes
        $attachments = [];
        if ($_FILES['attachment']['error'] == UPLOAD_ERR_OK) {
            $tmp_name = $_FILES['attachment']['tmp_name'];
            $name = $_FILES['attachment']['name'];
            $attachments[] = ['path' => $tmp_name, 'name' => $name];
        }
        
        $result = $this->mailServices->sendMail($email, $message, $subject, 'info@kiracom.fr', $attachments);
        
        // Traiter le résultat (par exemple, afficher un message à l'utilisateur)
        if ($result) {
            $alertValid = "E-mail envoyé avec succès!";
        } else {
            $alert = "Erreur lors de l'envoi de l'e-mail.";
        }
        }

    $this->view->render('layout2.php', 'mailTo.php', [
        'title' => 'Mail - APP.ILVDIGITAL',
        'resultatCheckPlaque' => $resultatCheckPlaque,
        'alert' => $alert,
        'alertValid' => $alertValid
        ]); 
}

public function dashboard()//dashboard view 
{

   if (!isset($_SESSION['user'])) {
      // Rediriger vers la page de connexion si l'utilisateur n'est pas connecté
      header("Location: join");
      exit();
   }

   $user_id = isset($_SESSION['user']['id'])? $_SESSION['user']['id'] : null;
   $email = $_SESSION['user']['email'];
   $token_plate = isset($_SESSION['getToken']) ? $_SESSION['getToken'] : null;
   
   $alert = '';
   $alertValid = '';
   $typeerror = '';
   $message = ""; 
   // Récupérez les plaques de l'utilisateur
   $plaques = $this->PlatesModel->getPlaquesUserInDashbord($user_id);
    
   $plateWithToken = $this->PlatesModel->getPlateWhithTokenForActivation($token_plate);

   if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
      if (isset($_POST['active_plate'])) {
          // Activation de la plaque

          $new_link_plaque =  isset($_POST['link_plate']) ? $_POST['link_plate'] : '';
          /* check entrer client  */
          if($plateWithToken['category_id']== 5){
          $filtered_url = filter_var($new_link_plaque, FILTER_VALIDATE_EMAIL);
          $typeerror = 'E-mail';

          }elseif($plateWithToken['category_id']== 1 ){
           $filtered_url = preg_match("/^[a-zA-ZÀ-ÿ\s]+$/", $new_link_plaque) ; 
           $typeerror = 'Le nom de l\'établissement';
          } 
          else{
          $filtered_url = filter_var($new_link_plaque, FILTER_VALIDATE_URL);
          $typeerror = 'L\'URL';
          }

          if ($filtered_url !== false ) {
            /* link reviws google */
            if($plateWithToken['category_id']== 1){
            /* recherche nom etablissment et crée le lien avis google   */
            $apiKey = $_ENV['APIKEY_GOOGLE_MAPS'];
            $generator = new GoogleReviewsLinkGenerator($apiKey);
            $linkGoogleReviews = $generator->genererLienAvis($new_link_plaque);
            
            $q = $this->PlatesModel->setPlqueActive($token_plate, $linkGoogleReviews , $user_id);

            }else{
             $q = $this->PlatesModel->setPlqueActive($token_plate, $new_link_plaque, $user_id);
            }
          if ($q) {
              /* stoper la saission aprés avoir activer le lien */
              unset($_SESSION['getToken']);
  
              // Envoyer l'e-mail de confirmation d'activation de plaque 
              $subject = 'Activation de votre plaque';
              $messageEmail = "
                  <p>Merci d'avoir choisi ILV DIGITAL, votre plaque " . $this->CategoryPlatesModel->getCategoryName($plateWithToken['category_id'])['category_name'] . " est activée.</p>
                  <p><img src='https://app.ilvdigital.fr/". $plateWithToken['img_qr_code'] ."' alt='Plaque ILV DIGITAL' width='200' height='200'></p>
              ";
              $headers = 'info@kiracom.fr';
  
              $this->mail->sendMail($email, utf8_decode($messageEmail), $subject, $headers);
              $alertValid = 'Votre plaque est activée';
              header("Location: dashboard");
              exit();
          } else {
              $alert = 'Veuillez réessayer!';
          }
        }else{
            $alert = $typeerror . " n'est pas valide.";
        }

      }
  /* update */
      if (isset($_POST['update_plate_link'])) {
          // Mise à jour du lien de la plaque
          $new_link = isset($_POST['update_link']) ? $_POST['update_link'] : '';
          $plate_id = $_POST['plate_id'];
          $categoryId = $this->PlatesModel->getCateygoryPlate($plate_id);
       
           
            /* check entrer client avant la mise à jour */
            if ($categoryId["category_id"] == 5){
                $filtered_url = filter_var($new_link, FILTER_VALIDATE_EMAIL);
                $typeerror = 'Email';
            }elseif($categoryId["category_id"] == 1 ){

                $filtered_url = preg_match("/^[a-zA-ZÀ-ÿ\s]+$/", $new_link) ; 
                $typeerror = 'Le nom de l\'établissement';

            }else{
                $filtered_url = filter_var($new_link, FILTER_VALIDATE_URL);
                $typeerror = 'L\'URL';
            }
     

          if ($filtered_url !== false) {
              if( $categoryId["category_id"] == 1 ){

                $apiKey = $_ENV['APIKEY_GOOGLE_MAPS'];
                $generator = new GoogleReviewsLinkGenerator($apiKey);
                $UpdateLinkGoogleReviews = $generator->genererLienAvis($new_link);
                $r = $this->PlatesModel->updateLinkPlate($plate_id, $UpdateLinkGoogleReviews);

            }else{
                $r = $this->PlatesModel->updateLinkPlate($plate_id, $new_link);
            }
          

          if ($r) {
              $alertValid = 'Votre plaque est à jour avec le nouveau lien.';
          } else {
              $alert = 'Veuillez réessayer!';
          }
        } else {
            $alert = $typeerror . " n'est pas valide.";
        } 
      
      }
      
      if(isset($_POST['reset-plate']))
      {
        $plate_id = $_POST['plate_id'];
        $tekon_plate = $this->PlatesModel->getTokenPlateAlreadyActive($plate_id);
        $base_link = "https://app.ilvdigital.fr/qrCode/". $tekon_plate["activation_token"];
        $active_plate = 0 ;
        $user_id = null ; 
        $query = $this->PlatesModel->restPlate($plate_id, $base_link , $active_plate, $user_id);
        if($query){
            $alert = "Votre plaque est réinitialiser !";
            header("Location: dashboard");
            exit();
        }else{
            $alert = "veuillez réessayer ultérieurement!";
        }
      }

  }
   
   $this->view->render('layout.php', 'dashboard.php', [
   'title' => 'APP.ILVDIGITAL | Tableau de bord',
   'message' => '',
   'plaques' => $plaques,
   'plateWithToken' => $plateWithToken,
   'alert' => $alert,
   'alertValid' => $alertValid,
   'message' => $message,
   ]); 
}// end dashboard view 




/* check plate */
public function checkPlaque($token_plaque)/* check plaque for regster */
   {
      $result = $this->PlatesModel->obtenirLienRedirection($token_plaque);

      if($result !== null){
         return [$result->url_qr_code, $result->active_plate, $result->category_id];
      }
      return ['',''];
   }
/* end /* check plate */


}


?>