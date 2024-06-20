<?php
//name space controller

namespace controller;

//import class model users
use models\Mailing;
use models\UsersModel;
use controller\PlatesController;
use helpers\ViewRenderer;
session_start();

//start class user controlet
class UserController
{

   private $usersModel;
   private $PlatesModel;
   private $view;
   private $mail;
   public function __construct()
   {
      $this->usersModel = new UsersModel;
      $this->PlatesController= new PlatesController;
      $this->view = new ViewRenderer;
      $this->mail = new Mailing;
   }



public function join()
{

    $signUpResult = $this->signUpUser();
    $loginResult = $this->login();
    // Initialiser le tableau résultat
    $result = [
    'message' => '',
    'alerts' => '',
    'alertValids' => '',
    ];
    // Concaténer les messages d'erreur
    $result['message'] .= $signUpResult['message'];
    $result['message'] .= $loginResult['message'];

   // Vous pouvez également faire de même pour les autres clés si nécessaire
   $result['alerts'] .= $signUpResult['alerts'];
   $result['alertValids'] .= $signUpResult['alertValids'];
   $result['alerts'] .= $loginResult['alerts'];
   $result['alertValids'] .= $loginResult['alertValids'];

  
    // Passer les résultats à la vue
    $this->view->render('layout.php', 'join.php', [
        'title' => 'Rejoindre - APP.ILVDIGITAL',
        'message' => $result['message'],
        'alerts' => $result['alerts'],
        'alertValid' => $result['alertValids'],
    ]);
}


public function signUpUser()
{
    $result = [
        'message' => '',
        'alerts' => '',
        'alertValids' => '',
    ];

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['signup'])) {
        $email = $_POST['email'];
        $errors = $this->validateAccountData($email, $_POST['mdp'],$_POST['user_name']);
       
        if (!empty($errors)) {
            foreach ($errors as $error) {
                $result['message'] .= "$error";
            }
        }

         // Vérification du jeton CSRF
        if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        $result['alerts'] = '/erreur';
        return $result;
        }

        if (empty($errors)) {
            // Fonction pour générer un jeton aléatoire
            function generateToken($length = 32)
            {
                return bin2hex(random_bytes($length));
            }

            $token = generateToken();
            
            // Envoyer l'e-mail de confirmation
            $subject = 'Confirmation d\'inscription';
            $messageEmail = "Bienvenue ! Cliquez sur le lien suivant pour confirmer votre adresse e-mail: https://app.ilvdigital.fr/validationemail/$token"; 
            
            $headers = 'info@kiracom.fr';

            $return = $this->mail->sendMail($email, $messageEmail, $subject, $headers);

            if ($return) {
                $q = $this->usersModel->insertUser($email,$_POST['user_name'], $_POST['mdp'], $token);

                if ($q) {
                    $result['alertValids']= '<p>Création de compte effectuée avec succès ! </p>';
                    $result['alertValids']= 'Un e-mail de confirmation a été envoyé à votre adresse e-mail. Veuillez vérifier votre boîte de réception.';
                    
                } else {
                    $result['alerts'] = "Veuillez réessayer.";
                }
            } else {
                $result['alerts'] = "Veuillez réessayer.";
            }
        }
    }

    return $result;
}
// end function signup
public function login()
{
    $result = [
        'message' => '',
        'alerts' => '',
        'alertValids' => '',
    ];

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])) {
        $email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
        $password = $_POST['mdp'];


        if (!$email || empty($password)) {
            $result['message'] = '<p>Entrées invalides. Veuillez fournir un email et un mot de passe valides.</p>';
            return $result;
        }
         // Vérification du jeton CSRF
         if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
            $result['alerts'] = '/erreur';
            return $result;
        }
    
        $loginResult = $this->usersModel->loginUser($email, $password);

        if ($loginResult === true) {
            $result['alerts'] = 'vous êtes connecté';
        } elseif ($loginResult === 'utilisateur_inexistant') {
            $result['message'] = '<p>Email et Mot de passe incorrect.</p>';
        } elseif ($loginResult === 'mot_de_passe_incorrect') {
            $result['message'] = '<p>Email et Mot de passe incorrect.</p>';
        } else {
            $result['message'] = '<p>Erreur de connexion. Veuillez réessayer.</p>';
        }
    }

    return $result;
}
// en login function 


/* passord reset link */
public function passwordReset()
{

$alert ='';
$message ='';
$alertValid ='' ;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_POST['account-password-recovery']) {
    $email = isset($_POST['email']) ? $_POST['email'] : '';
   function generateToken($length = 32)
   {
   return bin2hex(random_bytes($length));
   }
   if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
   // Générer un token unique (peut utiliser la fonction generateToken mentionnée précédemment)
   $token = generateToken();
   // Stocker le token dans la base de données avec l'e-mail associé
   $this->usersModel->setToken($email, $token);
   //  e-mail avec le lien de réinitialisation
   $resetLink = "https://app.ilvdigital.fr/newPassword/$token";
   $subject = 'Réinitialisation du mot de passe';
   $messageEmail = "Pour réinitialiser votre mot de passe, cliquez sur le lien suivant : $resetLink";
   $headers = "info@kiracom.fr";
   
   $mail = new Mailing ; 

   $r = $mail->sendMail($email,$messageEmail,$subject,$headers);
   if($r){ $alertValid = 'Un e-mail de réinitialisation a été envoyé à votre adresse e-mail.';}
   else{
      $alert = 'Veuillez réessayer.' ; 
   }
 }else {
    $alert = "L'adresse email n'est pas valide.";
 }
  
}
$this->view->render('layout.php', 'reset_passord_request.php', [
   'title' => 'APP.ILVDIGITAL | Réinitialisation mot de passe',
   'message' => $message,
   'alertValid' => $alertValid,
   'alert' => $alert,
]);
}
/* end passord reset link */

/* new password  */
public function newPassword()
{   
    $alertValid = '';
    $alert = '';
    $message = '';

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $token = $_POST['token'];
        $password = $_POST['password'];
        $passwordConfirm = $_POST['passwordConfirm'];

        // Validation des champs vides
        if (!empty($password) && !empty($passwordConfirm)) {
            // Validation de correspondance des mots de passe
            if ($password === $passwordConfirm) {
                // Validation de la complexité du mot de passe avec une regex
                if (preg_match('/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[!@#$%^&*()_+{}\[\]:;<>,.?~\\-]).{8,}$/', $password)) {
                    $newPassword = password_hash($password, PASSWORD_BCRYPT);
                    $q = $this->usersModel->setPassword($newPassword, $token);

                    if ($q) {
                        $alertValid = 'Votre mot de passe a été réinitialisé avec succès.';
                    } else {
                        $alert = 'Veuillez réessayer.';
                    }
                } else {
                    $message = 'Le mot de passe doit contenir au moins 8 caractères, une minuscule, une majuscule, un chiffre et un caractère spécial.';
                }
            } else {
                $message = "Les mots de passe ne correspondent pas.";
            }
        } else {
            $message = "Champs requis.";
        }
    }

    $this->view->render('layout.php', 'newPassword.php', [
        'title' => 'APP.ILVDIGITAL | Réinitialisation mot de passe',
        'message' => $message,
        'alert' => $alert,
        'alertValid' => $alertValid
    ]);
}
/* end new password */
/* account settings */
public function accountSettings()
{
    $alertValid = '' ;
    $alert = ''  ;
    $message = '' ; 

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $id = $_SESSION['user']['id']; 
        $email = $_SESSION['user']['email'];
        $userName = $_POST['userName'];

        $errors = $this->validateAccountData($email, $_POST['password'],$userName,$validateEmail = false);
        
        if (!empty($errors)) {
            foreach ($errors as $error) {
                $alert = "$error";
            }
        }
        if (empty($errors)) {
        $newPassword = password_hash($_POST['password'], PASSWORD_BCRYPT);

        $q = $this->usersModel->updateAccount($userName, $newPassword, $id);
        if($q){
           $alertValid =  'Votre compte a été réinitialisé avec succès.';
        }else{
           $alert = 'Veuillez réessayer.' ; 
        }
      }
    }
    $this->view->render('layout.php', 'accountsettings.php', [
        'title' => 'Profile - APP.ILVDIGITAL ' ,
        'message' => '' ,
        'alert' => $alert,
        'alertValid'  => $alertValid,
     ]);

}
/* end account settings */

/* validation de compte d'email client */

public function validationEmail()
{
    $alertValid = '';
    $alert = '';
    $confirm_token = isset($_GET['token']) ? $_GET['token'] : '';
    
    // Appel de la fonction pour récupérer les messages
    $messages = $this->usersModel->getUserByTekon($confirm_token);

    // Utilisez les messages dans votre logique
    if (isset($messages['success'])) {
        $alertValid = $messages['success'];
    } elseif (isset($messages['error'])) {
        $alert = $messages['error'];
    }

    $this->view->render('layout.php', 'validationEmail.php', [
        'title' => 'Validation de compte- APP.ILVDIGITAL',
        'alert' => $alert,
        'alertValid'  => $alertValid,
    ]);
}

/* end confirmation  */
public function validateAccountData($email, $password, $user_name, $validateEmail = true) {
    $erreurs = [];

    // Validation de l'email uniquement si $validateEmail est true
    if ($validateEmail) {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $erreurs[] = "L'adresse email n'est pas valide.";
        }
        if ($this->usersModel->emailExists($email)) {
            $erreurs[] = "L'adresse email est déjà utilisée";
        }
    }

    // Validation du mot de passe avec une expression régulière (au moins 8 caractères)
    if (!preg_match('/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[!@#$%^&*()_+{}\[\]:;<>,.?~\\-]).{8,}$/', $password)) {
        $erreurs[] = "Le mot de passe doit contenir au moins 8 caractères, une minuscule, une majuscule, un chiffre et un caractère spécial.";
    }

    // Validation du nom et prénom avec une expression régulière (lettres, espaces et tirets)
    if (!preg_match('/^[a-zA-Z -]+$/', $user_name)) {
        $erreurs[] = "Le nom et prénom ne doivent contenir que des lettres, des espaces et des tirets.";
    }

    return $erreurs;
}

 

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

   /* 404 */
   public function handle404Error()
   {
       $this->view->render('layout.php', '404.php', [
           'title' => '404 - APP.ILVDIGITAL' ,
        ]);
   }
   

   public function logout()
   {
       if (isset($_GET['action']) && $_GET['action'] == 'logout') {
           if ($this->connect()) {
               unset($_SESSION['user']);
           }
           // Rediriger vers l'action join
           header('location: join');
           exit();
       }
   }

}//class user controlet end