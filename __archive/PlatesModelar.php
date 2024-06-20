<?php
namespace models;
use PDO;
use PDOException;
use database\Database;

class PlatesModel
{
    private $db;

    public function __construct()
   {
      $this->db = new Database;
   }

   public function setPlqueActive($activation_token, $nouveau_lien, $user_id ) {
    $active = 1 ;
    $query = "UPDATE plaques SET url_qr_code = :lien_plaque, active_plate = :plaque_active , user_id = :user_id  WHERE activation_token = :activation_token";
    $statement = $this->db->getDb('./config/config.ini')->prepare($query);
    $statement->bindParam(':lien_plaque', $nouveau_lien);
    $statement->bindParam(':plaque_active', $active);
    $statement->bindParam(':user_id', $user_id);
    $statement->bindParam(':activation_token', $activation_token);
    $q = $statement->execute();
    $statement->closeCursor();
    if ($q) {
        return true;
    } else {
        return false;
    }
}
   public function updateLinkPlate($id, $new_link) {
    $query = "UPDATE plaques SET url_qr_code = :lien_plaque WHERE id = :id";
    $statement = $this->db->getDb('./config/config.ini')->prepare($query);
    $statement->bindParam(':lien_plaque', $new_link);
    $statement->bindParam(':id', $id);
    $q = $statement->execute();
    $statement->closeCursor();
    if ($q) {
        return true;
    } else {
        return false;
    }
}

// obetenir le lien de redircion 
function obtenirLienRedirection($activation_token) {

    $stmt = $this->db->getDb('./config/config.ini')->prepare("SELECT url_qr_code , active_plate, category_id FROM plaques WHERE activation_token = ?");

    $stmt->execute([$activation_token]);

    $result = $stmt->fetch(PDO::FETCH_OBJ);
    $stmt->closeCursor();
    if ($result !== false) {
        return $result;
    } else {
        return null;
    }
} // end get link redirect

/* function get plaque user */
function getPlaquesUserInDashbord($user_id){

    // Préparez votre requête SQL
    $query = "SELECT * FROM plaques WHERE user_id = :user_id order by id DESC";
    $statement = $this->db->getDb('./config/config.ini')->prepare($query);

    // Liez le paramètre
    $statement->bindParam(":user_id", $user_id, PDO::PARAM_INT);

    // Exécutez la requête
    $statement->execute();
    // Obtenez le nombre de résultats
    $rowCount = $statement->rowCount();

    // Obtenez le résultat de la requête
    $result = $statement->fetchAll(PDO::FETCH_ASSOC);
    $statement->closeCursor();
    return [
        'rowCount' => $rowCount,
        'result' => $result,
    ];

}/* end function get plaque user */

/* function get plaque user */
function getPlateWhithTokenForActivation($token_plate){

    // Préparez votre requête SQL
    $query = "SELECT * FROM plaques WHERE activation_token = :token_plate";
    $statement = $this->db->getDb('./config/config.ini')->prepare($query);

    // Liez le paramètre
    $statement->bindParam(":token_plate", $token_plate, PDO::PARAM_STR);

    // Exécutez la requête
    $statement->execute();

    // Obtenez le résultat de la requête
    $result = $statement->fetch(PDO::FETCH_ASSOC);
    $statement->closeCursor();
    return $result; // Fermez le statement

}/* end function get plaque user */

    
}


?>