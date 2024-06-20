<?php
//name space controller
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
namespace controller;

//import class model users
use Model\Mailing;
use Model\Model;
session_start();
//start class user controlet
class Controller
{

   private $db;

   public function __construct()
   {
      $this->db = new Model;
   }
      //root
   public function handleRequest()
   {
   
      $action = isset($_GET['action']) ? $_GET['action'] : 'home';
   
      try
      {
         
      if( $action == 'emailsignup'){
         $this->signUpUser();
      }elseif($action == 'home'){
         $this->login();
      }elseif($action == 'dash'){
         $this->dashboard();
      }elseif($action == 'activation'){
         $this->activeplq();
      }elseif($action == 'passwordreset'){

      }elseif($action == 'addplq'){
         $this->addplq();
      }elseif($action == 'passwordreset'){
         $this->passwordReset();
      }elseif($action == 'newPassword'){
         $this->newPassword();
      } else {
         throw new \Exception("404 error : La page n'a pas été trouvé !");
      }
      } catch (\Exception $e) {
      echo "🛑 Une erreur s'est produite : 💬 " . $e->getMessage();
      }
   } //end root 

   // render view
   public function render($layout, $template, $parameters = [])
   {
        // extract() : fonction prédéfinie qui permet d'extraire chaque indice d'un tableau sous forme de variable
      extract($parameters);
        // permet de faire une mise en mémoire tampon, on commence à garder en mémoire de la données
      ob_start();
        // Cette inclusion sera stockée directement dans la variable $content
      require_once "app/views/$template";
        // on stock dans la variable $content le template
      $content = ob_get_clean();
        // On temporise la sortie d'affichage
      ob_start();
        // On inclue le layout qui est le gabarit de base (header/nav/footer)
      require_once "app/views/$layout";
        // ob_end_flush() va libérer et fait tout apparaître dans le navigateur
      return ob_end_flush();
   }// render view

   private function validateUserData($email, $password) {
      $erreurs = [];

      // Vérifier si l'email existe déjà dans la base de données
      $userModel = new Model;
      

      // Validation de l'email avec une expression régulière pour un format valide
      if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
         $erreurs[] = "L'adresse email n'est pas valide.";
      }
      if ($userModel->emailExists($email)) {
         $erreurs[] = "L'adresse email est déjà utilisée";
      }
      // Validation du mot de passe avec une expression régulière (au moins 8 caractères)
      if (!preg_match('/^.{8,}$/', $password)) {
         $erreurs[] = "Le mot de passe doit contenir au moins 8 caractères.";
      }
   
      return $erreurs;
}



public function dashboard()//dashboard view 
{
  // Vérifier si l'utilisateur est connecté
/* if (!isset($_SESSION['user']['id'])) {
   // Rediriger vers la page de connexion si l'utilisateur n'est pas connecté
   header("Location:?action=home");
   exit();
} */

$alert = ''; // Initialisez votre alerte avec une chaîne vide

// Récupérez l'ID de l'utilisateur à partir de la session
$user_id = $_SESSION['user']['id'];

// Récupérez les plaques de l'utilisateur
$plaques = $this->db->getPlaquesUserInDashbord($user_id);

// Traitez le formulaire s'il est soumis


// Rendre la vue du tableau de bord avec les données nécessaires
$this->render('layout.php', 'dashboard.php', [
   'title' => 'Tableau de bord',
   'message' => '',
   'plaques' => $plaques,
   'alert' => $alert,
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
      $this->db->addUserPalqueDashbord($numUniquePlaque, $user_id);
   
      // Mise à jour de l'alerte avec un message de succès
      $alert = 'Plaque ajoutée avec succès!';
   }

   $this->render('layout.php', 'addplquser.php', [
      'title' => 'Ajout Plaques',
      'message' => '',
      'alert' => $alert,
   ]);
} 
/* end add user plaque */

public function activeplq()//activation de plaque
{
   
   $alert='';
   $alertValid  = '';
   if($_POST){
      $numUniquePlaque = $_POST['plq'];
      $new_link_plaque = $_POST['link_plaque'];

      $q= $this->db->setPlqueActive($numUniquePlaque, $new_link_plaque);
      if($q){
         $alertValid = 'Votre plaque est activé ' ;
      }

   }
   $this->render('layout.php', 'activePalque.php', [
      'title' => 'Activation Plaque',
      'message' => '',
      'alert' => $alert,
      'alertValid' => $alertValid,
   ]);
   
}// activation de plaque 


public function checkPlaque($token_plaque)/* check plaque for regster */
   {
      $result = $this->db->obtenirLienRedirection($token_plaque);

      if($result !== null){
         return [$result->lien_plaque, $result->plaque_active];
      }
      return ['',''];
   }/* end check plaque for regster*/



public function signUpUser()
{
   $alert = ''; // Définir une valeur par défaut
   $alertValid = '';
   $alertValids= [] ;
   $message = '';
   $error = false; // Déclarer la variable $error

   if (isset($_POST['signup'])) {
      
     
      $erreurs = $this->validateUserData($_POST['email'], $_POST['mdp']);
      
      if (!empty($erreurs)) {
            $error = true;

            $message .= '<p>Erreurs lors de la validation des données :</p>';
            $message .= '<div>';
            $message .= '<ul>';
            foreach ($erreurs as $erreur) {
            $message .= "<li>$erreur</li>";
            }
            $message .= '</ul>';
            $message .= '</div>';
      }

      if (!$error) {
            // Fonction pour générer un jeton aléatoire
            function generateToken($length = 32)
            {
            return bin2hex(random_bytes($length));
            }

            $token = generateToken();
            $email = htmlspecialchars($_POST['email'], ENT_QUOTES, 'UTF-8');
                // Envoyer l'e-mail de confirmation
            $subject = 'Confirmation d\'inscription';
            $messageEmail = "Bienvenue ! Cliquez sur le lien suivant pour confirmer votre adresse e-mail: http://http://app.ilvdigital.fr/?action=confirmation&token=$token";
            $headers = 'info@kiracom.fr';
            

            $mail = new Mailing;
            $return = $mail->sendMail($email, $messageEmail,$subject, $headers);
            if($return){
               $this->db->insertUser($email, $_POST['mdp'], $token);
               $alertValids[] = '<p>Création de compte effectuée avec succès ! </p>' ;
               $alertValids[] = 'Un e-mail de confirmation a été envoyé à votre adresse e-mail. Veuillez vérifier votre boîte de réception.';
               $alertValid .= '<ul>';
               foreach ($alertValids as $alertV) {
               $alertValid .= "<li>$alertV</li>";
               }
               $alertValid .= '</ul>';
            }else{
               $alert ="Veuillez réessayer.";
            }
      }
   }

   $this->render('layout.php', 'signup.php', [
      'title' => 'Accueil',
      'message' => '',
      'alert' => $alert,
      'alertValid' => $alertValid,
      'message' => $message,
   ]);
}
// end function signup

public function login()// login function

{  
   $alert ='';
   $message ='' ; 
   if($_SERVER['REQUEST_METHOD'] === 'POST'){
    
         $result = $this->db->loginUser($_POST['email'], $_POST['mdp']);
         if ($result === true) {
            if(isset($_SESSION['message'])){
               $alert = $_SESSION['message'];
               header("Location: ?action=dash");
            }
         }if ($result === 'utilisateur_inexistant') {
            $message ='<p>Utilisateur inexistant. Veuillez vérifier votre email.</p>';
         } elseif ($result === 'mot_de_passe_incorrect') {
            $message ='<p>Mot de passe incorrect. Veuillez réessayer."</p>';
         } elseif(!$result) {
            $message = '<p>Erreur de connexion. Veuillez réessayer."</p>';
         }
   }
   $this->render('layout.php', 'home.php', [
      'title' => 'Accueil Connexion',
      'message' => $message,
      'alert' => $alert,
   ]);
}// en login function 

/* passord reset link */
public function passwordReset()
{

$alert ='';
$message ='';
$alertValid ='' ;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
   $email = $_POST['email'];
   function generateToken($length = 32)
   {
   return bin2hex(random_bytes($length));
   }
   // Générer un token unique (peut utiliser la fonction generateToken mentionnée précédemment)
   $token = generateToken();
   // Stocker le token dans la base de données avec l'e-mail associé
   $this->db->setToken($email, $token);
   //  e-mail avec le lien de réinitialisation
   $resetLink = "http://localhost/Protfoliopourtous/?action=newPassword&token=$token";
   $subject = 'Réinitialisation du mot de passe';
   $messageEmail = "Pour réinitialiser votre mot de passe, cliquez sur le lien suivant : $resetLink";
   $headers = "info@kiracom.fr";
   
   $mail = new Mailing ; 

   $r = $mail->sendMail($email,$messageEmail,$subject,$headers);
   if($r){ $alertValid = 'Un e-mail de réinitialisation a été envoyé à votre adresse e-mail.';}
   else{
      $alert = 'Veuillez réessayer.' ; 
   }
  
}
$this->render('layout.php', 'reset_passord_request.php', [
   'title' => 'ItPortfolio',
   'message' => $message,
   'alertValid' => $alertValid,
   'alert' => $alert,
]);
}
/* end passord reset link */

/* new password  */
public function newPassword()
{
   $alertValid = '' ;
   $alert = ''  ;
   $message = '' ; 
   if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      $token = $_POST['token'];
 
      $newPassword = password_hash($_POST['password'], PASSWORD_BCRYPT);
      
      $q = $this->db->setPassword($newPassword, $token);
      if($q){
         $alertValid =  'Votre mot de passe a été réinitialisé avec succès.';
      }else{
         $alert = 'Veuillez réessayer.' ; 
      }
      
}

   $this->render('layout.php', 'newPassword.php', [
      'title' => '' ,
      'message' => '' ,
      'alert' => $alert,
      'alertValid'  => $alertValid,
   ]);
}

/* end new password */
public function connect()
   {
      if (isset($_SESSION['user'])) {
            return true;
      } else {
            return false;
      }
   }

public function user()
   {
      if ($this->connect() && $_SESSION['user']['role'] == 'ROLE_USER') {
            return true;
      } else {
            return false;
      }
   }

public function admin()
   {
      if ($this->connect() && $_SESSION['user']['role'] == 'ROLE_ADMIN') {
            return true;
      } else {
            return false;
      }
   }

}//class user controlet end





