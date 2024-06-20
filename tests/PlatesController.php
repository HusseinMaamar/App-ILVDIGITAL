<?php 
//class plates
namespace controller;

use models\PlatesModel;
use models\CategoryPlatesModel;
use helpers\ViewRenderer;
use models\Mailing;

class PlatesController
{
    
   private $PlatesModel;
   private $CategoryPlatesModel;
   private $view;
   private $mail;

   public function __construct()
   {
      $this->PlatesModel = new PlatesModel;
      $this->CategoryPlatesModel = new CategoryPlatesModel;
      $this->view = new ViewRenderer;
      $this->mail = new Mailing;
   }  /// construct model and view


public function dashboard()//dashboard view 
{
   if (!isset($_SESSION['user']['id'])) {
      // Rediriger vers la page de connexion si l'utilisateur n'est pas connecté
      header("Location:?action=activation");
      exit();
   }

   $user_id = $_SESSION['user']['id'];
   $email = $_SESSION['user']['email'];
   $token_plate = isset($_SESSION['getToken']) ? $_SESSION['getToken'] : null;
   
   $alert = '';
   $alertValid = '';

   // Récupérez les plaques de l'utilisateur
   $plaques = $this->PlatesModel->getPlaquesUserInDashbord($user_id);
   $plateWithToken = $this->PlatesModel->getPlateWhithTokenForActivation($token_plate);

   if($_SERVER['REQUEST_METHOD'] === 'POST' && $_POST['active_plate']){
      
      $new_link_plaque = $_POST['link_plate'] ;
      $q= $this->PlatesModel->setPlqueActive($token_plate, $new_link_plaque, $user_id);
      if($q){
         unset($_SESSION['getToken']);
          // Envoyer l'e-mail de confirmation

         $subject = 'Activation de votre plaque';
        
         $messageEmail = "
         <p>Merci d'avoir choisi ILV DIGITAL, votre plaque : " . $this->CategoryPlatesModel->getCategoryId($plateWithToken['category_id']) . " est activée. Commencez à récolter des avis dès maintenant.</p>
         <p><img src='" . $plateWithToken['qr_code'] . "' alt='QR Code ILV DIGITAL' width='200' height='200'></p>
         ";
     
         $headers = 'info@kiracom.fr';
      
         $this->mail->sendMail($email,utf8_decode($messageEmail), $subject, $headers);
         $alertValid = 'Votre plaque est activé ' ;
      }

   }
   
   $this->view->render('layout.php', 'dashboard.php', [
   'title' => 'Tableau de bord',
   'message' => '',
   'plaques' => $plaques,
   'plateWithToken' => $plateWithToken,
   'alert' => $alert,
   'alertValid' => $alertValid,
   ]); 
}// end dashboard view 


/* add palques user */
public function addplq()
{
   
   if (!isset($_SESSION['user']['id'])) {
      // Rediriger vers la page de connexion si l'utilisateur n'est pas connecté
      header("Location:?action=home");
      exit();
   }

   $user_id = $_SESSION['user']['id'];
   $alert = '';

   if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      $numUniquePlaque = $_POST['numero_unique'];
    /*   $userId = $_POST['id']; */
      // Ajoutez la plaque à l'utilisateur
      $this->PlatesModel->addUserPalqueDashbord($numUniquePlaque, $user_id);
   
      // Mise à jour de l'alerte avec un message de succès
      $alert = 'Plaque ajoutée avec succès!';
   }

   $this->view->render('layout.php', 'addplquser.php', [
      'title' => 'Ajout Plaques',
      'message' => '',
      'alert' => $alert,
   ]);
} 
/* end add user plaque */

public function activePlate()//activation de plaque
{
   
   $alert='';
   $alertValid  = '';
   if($_POST){
      $token = $_POST['$activation_token'];
      $new_link_plaque = $_POST['link_plaque'];

      $q= $this->PlatesModel->setPlqueActive($token, $new_link_plaque);
      if($q){
         $alertValid = 'Votre plaque est activé ' ;
      }

   }
   $this->view->render('layout.php', 'activePlate.php', [
      'title' => 'Activation Plaque',
      'message' => '',
      'alert' => $alert,
      'alertValid' => $alertValid,
   ]);
   
}// activation de plaque 


public function checkPlaque($token_plaque)/* check plaque for regster */
   {
      $result = $this->PlatesModel->obtenirLienRedirection($token_plaque);

      if($result !== null){
         return [$result->lien_plaque, $result->plaque_active];
      }
      return ['',''];
   }/* end check plaque for regster*/


}


?>