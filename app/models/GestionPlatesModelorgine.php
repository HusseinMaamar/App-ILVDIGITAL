<?php
namespace models;
require_once 'vendor/autoload.php';
use PDO;
use PDOException;
use database\Database;
use BaconQrCode\Renderer\Image\Png;
use BaconQrCode\Writer;
use TCPDF;
use ZipArchive;
class GestionPlatesModel
{
    private $db;
    private $categoryPlate;

    public function __construct()
   {
      $this->db = new Database;
      $this->categoryPlate = new CategoryPlatesModel;

   }
  
   public function insertPlates($nombrePlaques, $categoryName, $destinationFolder) {
    // Préparation de la requête SQL
    $query = "INSERT INTO plaques (unique_number, url_qr_code, active_plate, activation_token, user_id, category_id, state_plate, img_qr_code) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    $statement = $this->db->getDb('./config/config.ini')->prepare($query);

    // Générer et insérer les plaques
    for ($i = 0; $i < $nombrePlaques; $i++) {
        $lastInsertedId = 0;
        $activationToken = $this->generateActivationToken();
        /* $lienPlaque = "https://app.ilvdigital.fr/?action=activation&token=$activationToken"; */
        $lienPlaque = "https://ilvdigital.fr"; 
        $qrCode = '';
        // Définir les valeurs pour chaque paramètre
        $plaqueActive = 0;
        $userId = null;
        $categoryInfo = $this->categoryPlate->getCategoryId($categoryName);
        $categoryId = $categoryInfo['id'];
        $etatPlaque = 0;

        $maxIterations = 1000;
        $iterationCount = 0;

        do {
            $numeroUnique = $this->generateNumeroUnique();
            $iterationCount++;
            if ($iterationCount > $maxIterations) {
                // Gérer le cas où la limite d'itérations est atteinte
                die("Erreur : Impossible de générer un numéro unique non duplicata après $maxIterations itérations.");
            }
        } while ($this->checkDuplicateNumeroUnique($numeroUnique));


        $statement->bindParam(1, $numeroUnique, \PDO::PARAM_STR);
        $statement->bindParam(2, $lienPlaque, \PDO::PARAM_STR);
        $statement->bindParam(3, $plaqueActive, \PDO::PARAM_INT);
        $statement->bindParam(4, $activationToken, \PDO::PARAM_STR);
        $statement->bindParam(5, $userId, \PDO::PARAM_INT);
        $statement->bindParam(6, $categoryId, \PDO::PARAM_INT);
        $statement->bindParam(7, $etatPlaque, \PDO::PARAM_INT);
        $statement->bindParam(8, $qrCode, \PDO::PARAM_STR);

        // Exécution de la requête
        $statement->execute();

        $lastInsertedId = $this->db->getDb('./config/config.ini')->lastInsertId();; 

        // Générer et sauvegarder le QR code après avoir récupéré l'ID
        $qrCode = $this->generateAndSaveLinkQrCode($lienPlaque, $numeroUnique, $destinationFolder, $lastInsertedId, $categoryId);

        // Mettez à jour le champ qr_code dans la base de données avec le chemin du fichier
        $statementUpdate = $this->db->getDb('./config/config.ini')->prepare("UPDATE plaques SET img_qr_code = ? WHERE id = ?");
        $statementUpdate->bindParam(1, $qrCode, \PDO::PARAM_STR);
        $statementUpdate->bindParam(2, $lastInsertedId, \PDO::PARAM_INT);
        $statementUpdate->execute();
        $statementUpdate->closeCursor();
    } 
    $statement->closeCursor();
    return "Commande réussie !";
}

// Fonction pour générer un numéro unique avec une longueur spécifique
public function generateNumeroUnique($length = 7) {
    // Générer une chaîne de caractères aléatoires
    $randomString = bin2hex(random_bytes($length));

    // Retourner les premiers caractères de la chaîne jusqu'à la longueur spécifiée
    return substr($randomString, 0, $length);
}/* end generet NU */

// Fonction pour générer un token
function generateActivationToken() {
    return bin2hex(random_bytes(16));
}/* tekon */

// Fonction pour générer un QR code pour un lien et l'enregistrer dans un fichier
public function generateAndSaveLinkQrCode($link, $numeroUniquePlaque, $destinationFolder, $lastInsertedId, $type_plate) {
    // Chemin de l'image de fond
    $backgroundImagePath = 'public/uploads/designBackground/' . $type_plate . '.png';

    // Utilisation de la classe appropriée de BaconQrCode
    $qrCodeRenderer = new Png;
    $qrCodeRenderer->setMargin(0);
    $qrCodeRenderer->setHeight(260);
    $qrCodeRenderer->setWidth(260);

    $writer = new Writer($qrCodeRenderer);
    $qrCodeImageString = $writer->writeString($link);

    // Charger le fond depuis l'image Canva
    $backgroundImage = imagecreatefrompng($backgroundImagePath);

    // Créer une ressource GD à partir de la chaîne du QR code
    $qrCodeImage = imagecreatefromstring($qrCodeImageString);

    // Vérifier si l'image a été correctement chargée
    if (!$qrCodeImage || !$backgroundImage) {
        die("Erreur lors du chargement de l'image QR code ou de l'image de fond");
    }

    // Activer l'alpha blending pour prendre en charge la transparence
    imagealphablending($backgroundImage, true);
    imagesavealpha($backgroundImage, true);

    // Superposer le QR code sur le fond
    imagecopy($backgroundImage, $qrCodeImage, 581, 500, 0, 0, imagesx($qrCodeImage), imagesy($qrCodeImage));

    // Ajouter le numéro unique comme texte sur l'image
    $textColor = imagecolorallocate($backgroundImage, 0, 0, 0); // Couleur du texte en noir
    $fontSize = 20;
    $fontPath = 'public/assets/fonts/Roboto_Condensed/RobotoCondensed-VariableFont_wght.ttf'; // Remplacez par le chemin de votre police TrueType
    $x = 40;
    $y = imagesy($backgroundImage) - 30;
    imagettftext($backgroundImage, $fontSize, 0, $x, $y, $textColor, $fontPath, $lastInsertedId);

    // Nom du fichier QR code basé sur le numéro unique de la plaque
    $filename = $lastInsertedId . '_' . $numeroUniquePlaque . '_qr_code.png';

    // Construire le chemin complet du fichier de destination
    $destinationPath = $destinationFolder . '/qrCodePng/' . $filename;

    // Enregistrer le QR code dans le fichier spécifié
    imagepng($backgroundImage, $destinationPath);

    // Convertir l'image en PDF
    $this->generateSinglePDF($backgroundImage, $lastInsertedId, $numeroUniquePlaque, $destinationFolder);

    // Libérer la mémoire
    imagedestroy($qrCodeImage);
    imagedestroy($backgroundImage);

    return $destinationPath;
}

public function generateSinglePDF($imageResource, $lastInsertedId, $numeroUniquePlaque, $destinationFolder) {
    // Créer une nouvelle instance de TCPDF
    $pdf = new TCPDF();
    // Set margin (optional, but can be useful to ensure there's space for your content)
    $pdf->SetMargins(10, 10, 10);

    // Disable header and footer
    $pdf->SetPrintHeader(false);
    $pdf->SetPrintFooter(false);
    // Ajouter une page au PDF
    $pdf->AddPage();

     // Enregistrer temporairement l'image en tant que fichier
     $tempImagePath = tempnam(sys_get_temp_dir(), 'qr_code');
     imagepng($imageResource, $tempImagePath);

    // Ajouter l'image au PDF
    $pdf->Image($tempImagePath , 10, 10, 50, 50);
 
      $cheminCompletActuel = __DIR__;
     // Partie à enlever
      $partieAEnlever = 'app\models';
     // Enlever la partie spécifiée du chemin
      $cheminModifie = str_replace($partieAEnlever, '', $cheminCompletActuel);
      // Obtenez la date actuelle au format "Y-m-d_H-i-s"
      $date = date("Y-m-d");

      // Nom du dossier basé sur la date et l'heure
      $dossierDateHeure = $cheminModifie . $destinationFolder . '/qrCodePdf/';
      
     // Créez le dossier s'il n'existe pas déjà
   /*   if (!file_exists($dossierDateHeure) && !is_dir($dossierDateHeure)) {
        mkdir($dossierDateHeure, 0755, true); // Créez le dossier avec les permissions appropriées
        } */
    // Nom du fichier PDF basé sur le numéro unique de la plaque
    $pdfFilename = $dossierDateHeure . $lastInsertedId . '_' . $numeroUniquePlaque . '_qr_code.pdf';

    // Output the PDF to a file
    $pdf->Output($pdfFilename, 'F');
    // Création de l'archive ZIP
      $zip = new ZipArchive;
      $archiveFilename = $dossierDateHeure .'commande_' . $date . '_archive.zip';
      if ($zip->open($archiveFilename, ZipArchive::CREATE) === TRUE) {
          // Ajoute le fichier PDF à l'archive
          $zip->addFile($pdfFilename, basename($pdfFilename));
          $zip->close();
      } else {
          echo 'Échec de la création de l\'archive ZIP.';
      }
  
      // Supprime le fichier PDF après l'ajout à l'archive
      unlink($pdfFilename);

    return $pdfFilename;
} /* end pdf */


public function checkDuplicateNumeroUnique($numeroUnique) {
    try {
        $query = "SELECT 1 FROM plaques WHERE unique_number = :numeroUnique LIMIT 1";
        $statement = $this->db->getDb('./config/config.ini')->prepare($query);
        $statement->bindParam(':numeroUnique', $numeroUnique);
        $statement->execute();

        // Obtenir le nombre de lignes résultantes
        $result = $statement->rowCount() > 0;

        // Fermer le statement pour libérer les ressources
        $statement->closeCursor();

        return $result;
    } catch (\Exception $e) {
        // Gérer l'erreur
        echo "Erreur : " . $e->getMessage();
        return false;
    }
}


}

