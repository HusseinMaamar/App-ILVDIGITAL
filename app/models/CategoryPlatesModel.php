<?php
namespace models;
use PDO;
use PDOException;
use database\Database;
class CategoryPlatesModel
{
    private $db;

    public function __construct()
   {
      $this->db = new Database;
   }
   
   public function getCategoryId($categoryName) {
      try {
          $query = "SELECT id FROM category_plaque WHERE category_name = :category_name";
          $statement = $this->db->getDb('./config/config.ini')->prepare($query);
          $statement->bindParam(':category_name', $categoryName);
          $statement->execute();
  
          // Vérifier si la requête a réussi
          if (!$statement) {
              throw new \Exception("Erreur lors de l'exécution de la requête.");
          }
  
          // Récupérer le résultat
          $result = $statement->fetch();
  
          // Fermer le statement pour libérer les ressources
          $statement->closeCursor();
  
          return $result;
      } catch (\Exception $e) {
          // Gérer l'erreur
          echo "Erreur : " . $e->getMessage();
          return false;
      }
  }
   public function getCategoryName($id) {
      try {
          $query = "SELECT category_name FROM category_plaque WHERE id = :id";
          $statement = $this->db->getDb('./config/config.ini')->prepare($query);
          $statement->bindParam(':id', $id);
          $statement->execute();
  
          // Vérifier si la requête a réussi
          if (!$statement) {
              throw new \Exception("Erreur lors de l'exécution de la requête.");
          }
  
          // Récupérer le résultat
          $result = $statement->fetch();
  
          // Fermer le statement pour libérer les ressources
          $statement->closeCursor();
  
          return $result;
      } catch (\Exception $e) {
          // Gérer l'erreur
          echo "Erreur : " . $e->getMessage();
          return false;
      }
  }

  public function getAllCategories() {
   try {
       $query = "SELECT category_name FROM category_plaque";
       $statement = $this->db->getDb('./config/config.ini')->query($query);

       // Vérifier si la requête a réussi
       if (!$statement) {
           throw new \Exception("Erreur lors de l'exécution de la requête.");
       }

       // Récupérer tous les résultats
       $results = $statement->fetchAll(\PDO::FETCH_ASSOC);

       // Fermer le statement pour libérer les ressources
       $statement->closeCursor();

       return $results;
   } catch (\Exception $e) {
       // Gérer l'erreur
       echo "Erreur : " . $e->getMessage();
       return false;
   }
}

  
}


?>