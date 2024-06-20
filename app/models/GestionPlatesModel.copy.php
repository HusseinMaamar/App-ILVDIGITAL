<?php
namespace models;
require_once 'vendor/autoload.php';
use PDO;
use PDOException;
use database\Database;
use BaconQrCode\Renderer\Image\Png;
use BaconQrCode\Writer;
use TCPDF;
use \setasign\Fpdi\Fpdi;
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
    $query = "INSERT INTO plaques (numero_unique, lien_plaque, plaque_active, activation_token, user_id, category_id, etat_plaque, qr_code) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    $statement = $this->db->getDb('./config/config.ini')->prepare($query);

    // Générer et insérer les plaques
    for ($i = 0; $i < $nombrePlaques; $i++) {
        $lastInsertedId = 0;
        $activationToken = $this->generateActivationToken();
        $lienPlaque = "https://app.ilvdigital.fr/?action=activation&token=$activationToken";
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
        $statementUpdate = $this->db->getDb('./config/config.ini')->prepare("UPDATE plaques SET qr_code = ? WHERE id = ?");
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

public function generateAndSaveLinkQrCode($link, $numeroUniquePlaque, $destinationFolder, $lastInsertedId, $type_plate) {
    // Utilisation de la classe appropriée de BaconQrCode
    $qrCodeRenderer = new Png;
    $qrCodeRenderer->setMargin(0);
    $qrCodeRenderer->setHeight(260);
    $qrCodeRenderer->setWidth(260);

    $writer = new Writer($qrCodeRenderer);
    $qrCodeImageString = $writer->writeString($link);

    // Créer une ressource GD à partir de la chaîne du QR code
    $qrCodeImage = imagecreatefromstring($qrCodeImageString);

    // Vérifier si l'image a été correctement chargée
    if (!$qrCodeImage) {
        die("Erreur lors du chargement de l'image QR code");
    }

    // Ajouter le numéro unique comme texte sur l'image
    $textColor = imagecolorallocate($qrCodeImage, 0, 0, 0); // Couleur du texte en noir
    $fontSize = 20;
    $fontPath = 'public/assets/fonts/Roboto_Condensed/RobotoCondensed-VariableFont_wght.ttf'; // Remplacez par le chemin de votre police TrueType
    $x = 10;
    $y = imagesy($qrCodeImage) - 10;
    imagettftext($qrCodeImage, $fontSize, 0, $x, $y, $textColor, $fontPath, $lastInsertedId);

    // Nom du fichier QR code basé sur le numéro unique de la plaque
    $filename = $lastInsertedId . '_' . $numeroUniquePlaque . '_qr_code.png';

    // Construire le chemin complet du fichier de destination
    $destinationPath = $destinationFolder . '/qrCodePng/' . $filename;

    // Enregistrer le QR code dans le fichier spécifié
    imagepng($qrCodeImage, $destinationPath);

    // Convertir l'image en PDF
    $this->generateSinglePDF($qrCodeImage, $lastInsertedId, $numeroUniquePlaque, $destinationFolder);

    // Libérer la mémoire
    imagedestroy($qrCodeImage);

    return $destinationPath;
}



public function generateSinglePDF($imageResource, $lastInsertedId, $numeroUniquePlaque, $destinationFolder) {
    // Créer une nouvelle instance de FPDI
    $pdf = new FPDI;

    // Ajouter chaque page du PDF d'origine
    $originalPdfPath = 'public/uploads/designBackground/avis google.pdf';
    $pageCount = $pdf->setSourceFile($originalPdfPath);

    for ($pageNumber = 1; $pageNumber <= $pageCount; $pageNumber++) {
        $template = $pdf->importPage($pageNumber);
        $pdf->AddPage();
        $pdf->useTemplate($template);

        // Enregistrer temporairement l'image en tant que fichier
        $tempImagePath = tempnam(sys_get_temp_dir(), 'qr_code');
        imagepng($imageResource, $tempImagePath);

        // Ajouter l'image au PDF
        $pdf->Image($tempImagePath, 10, 10, 50, 50);

        // Nom du fichier PDF basé sur le numéro unique de la plaque
        $pdfFilename = $destinationFolder . '/qrCodePdf/' . $lastInsertedId . '_' . $numeroUniquePlaque . '_qr_code.pdf';

        // Output the PDF to a file
        $pdf->Output($pdfFilename, 'F');

        // Création de l'archive ZIP
        $zip = new ZipArchive;
        $archiveFilename = $destinationFolder . '/commande_' . date("Y-m-d") . '_archive.zip';

        if ($zip->open($archiveFilename, ZipArchive::CREATE) === TRUE) {
            // Ajoute le fichier PDF à l'archive
            $zip->addFile($pdfFilename, basename($pdfFilename));
            $zip->close();
        } else {
            echo 'Échec de la création de l\'archive ZIP.';
        }

        // Supprime le fichier PDF temporaire après l'ajout à l'archive
        unlink($pdfFilename);
    }

    return $archiveFilename;
}





public function checkDuplicateNumeroUnique($numeroUnique) {
    try {
        $query = "SELECT 1 FROM plaques WHERE numero_unique = :numeroUnique LIMIT 1";
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

