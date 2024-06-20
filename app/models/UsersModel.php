<?php
namespace models;
use PDO;
use PDOException;
use database\Database;

// class Model users
class UsersModel
{
    private $db;

    public function __construct()
   {
    $this->db = new Database;
   }


   public function startSession() {
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }
   }
    /* check email user */
    public function emailExists($email) {

        $query = "SELECT COUNT(*) FROM users WHERE email = :email";
        $statement = $this->db->getDb('./config/config.ini')->prepare($query);
        $statement->bindParam(':email', $email);
        $statement->execute();

        $count = $statement->fetchColumn();
        $statement->closeCursor();
        return $count > 0;
    }/* check email user */

   /* update tekone */
    public function setToken($email, $token) {

        $query = "UPDATE users SET confirm_token = :token  WHERE email = :email";
        $statement = $this->db->getDb('./config/config.ini')->prepare($query);
        $statement->bindParam(':token', $token);
        $statement->bindParam(':email', $email);
        $statement->execute();
        $statement->closeCursor();
    }/* tekon */

    /* function update password reset */
    public function setPassword($password, $token) {

        $query = "UPDATE users SET pwd = :pwd  WHERE confirm_token = :token";
        $statement = $this->db->getDb('./config/config.ini')->prepare($query);
        $statement->bindParam(':pwd', $password);
        $statement->bindParam(':token', $token);
        $q = $statement->execute();
        $statement->closeCursor();
        if($q){
            return true ;
        }else{
            return false ;
        }
    }/* end function pwd reset */

    /* function update account*/
    public function updateAccount($userName,$password, $id) {

        $query = "UPDATE users SET userName = :userName, pwd = :pwd WHERE id = :id";
        $statement = $this->db->getDb('./config/config.ini')->prepare($query);
        $statement->bindParam(':userName', $userName);
        $statement->bindParam(':pwd', $password);
        $statement->bindParam(':id', $id);
        $q = $statement->execute();
        $statement->closeCursor();
        if($q){
            return true ;
        }else{
            return false ;
        }
    }/* end function update account*/

    // Méthode pour insérer un nouvel utilisateur dans la base de données
    public function insertUser($email, $user_name, $password, $token)
    {
        // Hasher le mot de passe avant de l'insérer dans la base de données
        $passordHash = password_hash($password, PASSWORD_DEFAULT);
        $role = 'ROLE_USER' ; 
        try {
            // Utiliser une requête préparée pour éviter les attaques par injection SQL
            $query = "INSERT INTO users ( email,userName, pwd, role, confirm_token) VALUES ( :email, :userName , :pwd, :role, :confirm_token)";
            $statement = $this->db->getDb('./config/config.ini')->prepare($query);
            
            $statement->bindParam(':email', $email);
            $statement->bindParam(':userName', $user_name);
            $statement->bindParam(':pwd', $passordHash);
            $statement->bindParam(':role', $role);
            $statement->bindParam(':confirm_token', $token);
            $result = $statement->execute();
            $user = $this->getUserByEmail($email);
            $statement->closeCursor();

           // Enregistrez des informations dans la session
           $this->startSession();
           $_SESSION['user'] = [
            'id' => $user['id'],
            'email' => $user['email'],
            'userName' => $user['userName'],
            'role' => $user['role'],
           ];

            return $result;

        } catch (PDOException $e) {
            // Gérer les erreurs d'insertion
            die("Erreur d'insertion : " . $e->getMessage());
        }
    }// end insert user 

    // Méthode pour obtenir un utilisateur par son adresse e-mail
    private function getUserByEmail($email)
     {
    $query = "SELECT id, email, userName, role FROM users WHERE email = :email";
    $statement = $this->db->getDb('./config/config.ini')->prepare($query);
    $statement->bindParam(':email', $email);
    $statement->execute();
    $user = $statement->fetch(PDO::FETCH_ASSOC);
    $statement->closeCursor();
    return $user;
     }
     /* validation d'email */
     public function getUserByTekon($confirm_token)
     {
         $pdo = $this->db->getDb('./config/config.ini');
         $messages = [];

         $query = "SELECT id, email, userName, role FROM users WHERE confirm_token = :confirm_token";
         $statement = $pdo->prepare($query);
         $statement->bindParam(':confirm_token', $confirm_token);
         $statement->execute();
         // Vérification de l'existence de l'utilisateur avec le token donné
         if ($statement->rowCount() > 0) {
             // Utilisateur trouvé, récupérer les données
             $user = $statement->fetch(PDO::FETCH_ASSOC);
     
             // Mettre à jour la base de données (exemplaire, ajustez selon vos besoins)
             $updateQuery = "UPDATE users SET is_activated = 1 WHERE id = :user_id";
             $updateStatement = $pdo->prepare($updateQuery);
             $updateStatement->bindParam(':user_id', $user['id']);
             $updateStatement->execute();
     
             $messages['success'] = "Votre compte ({$user['email']}) a été activé avec succès!";
         } else {
             // Aucun utilisateur trouvé avec le token donné
             $messages['error'] = "Aucun utilisateur trouvé avec le lien d'activation donné.";
         }
     
         $statement->closeCursor();
         $updateStatement->closeCursor();
     
         return $messages;
     }/* validation d'email */
     
   
    public function loginUser($email, $password) // login user
    {
        // Récupérer l'utilisateur avec l'email spécifié
        $query = "SELECT * FROM users WHERE email = :email";
        $statement = $this->db->getDb('./config/config.ini')->prepare($query);
        $statement->bindParam(':email', $email);
        $statement->execute();
        $user = $statement->fetch(PDO::FETCH_ASSOC);
        $statement->closeCursor();
        // Vérifier si l'utilisateur existe
        if (!$user) {
            return 'utilisateur_inexistant'; // L'utilisateur n'existe pas
        }

        // Vérifier le mot de passe
        if (password_verify($password, $user['pwd'])) {
            $this->startSession();
            $_SESSION['user'] = $user; 
            return true; // Mot de passe correct, retourner les données de l'utilisateur
        } else {
            return 'mot_de_passe_incorrect'; // Mot de passe incorrect
        }
    }// end login user


} // class users model end
