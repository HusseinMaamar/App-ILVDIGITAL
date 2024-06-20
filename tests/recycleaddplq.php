<?php   // Méthode pour enrgstrié  plaque
    public function newPlaque($numero_unique, $link_redirect, $user_id)
    {
            $active = 1 ; 
            /* $link_redirect = "http://localhost/Protfoliopourtous/?action=emailsignup&plq=$numero_unique" ;
            $user_id = null ; */
        try {
            // Utiliser une requête préparée pour éviter les attaques par injection SQL
            $query = "INSERT INTO plaques (numero_unique, lien_plaque, plaque_active, user_id) VALUES ( :numero_unique, :lien_plaque, :plaque_active , :user_id)";
            $statement = $this->getDb('./config/config.ini')->prepare($query);
            $statement->bindParam(':numero_unique', $numero_unique);
            $statement->bindParam(':lien_plaque', $link_redirect);
            $statement->bindParam(':plaque_active', $active);
            $statement->bindParam(':user_id', $user_id);
            $statement->execute();
        } catch (PDOException $e) {
            // Gérer les erreurs d'insertion
            die("Erreur d'insertion : " . $e->getMessage());
        }
    }// end insert user  ?>



/* // Fonction d'activation de la plaque
// Fonction de mise à jour du lien de la plaque après l'activation
function mettreAJourLienPlaque($numero_unique, $nouveau_lien) {
    global $conn;

    $stmt = $conn->prepare("UPDATE plaques SET lien_plaque = ?  WHERE numero_unique = ?");
    $stmt->bind_param("ss", $nouveau_lien, $numero_unique );

    if ($stmt->execute()) {
        echo "Lien de la plaque mis à jour avec succès!";
    } else {
        echo "Erreur lors de la mise à jour du lien de la plaque : " . $stmt->error;
    }

    $stmt->close();
}//end */



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
}/* end add user plaque */


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


    <!-- <div class="tabs">
        <button class="tablink" onclick="openTab('Inscription')" >Inscription</button>
        <button class="tablink" onclick="openTab('Connexion')">Connexion</button>
    </div> -->