<?php 
//class Gestion plates
namespace controller;

use models\GestionPlatesModel;
use helpers\ViewRenderer;

class GestionPlatesController
{
    private $db;
    private $view;
 
    public function __construct()
    {
       $this->db = new GestionPlatesModel;
       $this->view = new ViewRenderer;
    }  /// construct Gestionmodel and view
     //order admin plates
     public function handelInsertPlate(){
        $alert = '';
        $alertValid = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Chemin complet du fichier actuel
            
            $nombrePlaques = filter_var($_POST['nombre_plaques'], FILTER_VALIDATE_INT);
            $categoryId = filter_var($_POST['categorie'], FILTER_SANITIZE_STRING);
            $destinationFolder ='public/uploads';
          
           $q = $this->db->insertPlates($nombrePlaques,$categoryId, $destinationFolder);
      
           if($q){
            $alertValid = 'Plaque ajoutée avec succès!';
           }else{
            $alert = 'Plaque non trouvé vérifier le numéro de la plaque';
           }

         }
         $this->view->render('layout.php', 'dashboard.php', [
            'title' => 'Tableau de bord Admin',
            'message' => '',
            'alert' => $alert,
            'alertValid' => $alertValid,
            ]); 
     } //end order plates

     /* download archiv.zip order */
     public function downloadFolderArchiveZip(){
        $alert = '';
        $alertValid = '';

        // Chemin du répertoire où vous stockez vos dossiers
         $cheminDossiers = 'public/uploads/qrCodePdf';

       // Récupère la liste des dossiers dans le répertoire
       $dossiers = array_diff(scandir($cheminDossiers), array('..', '.'));

       if ($_SERVER["REQUEST_METHOD"] === "POST") {
         // Vérifier si le dossier est sélectionné
         if (isset($_POST['dossier'])) {
             $dossierSelectionne = $_POST['dossier'];
              //print_r($dossierSelectionne); 
             // Chemin du fichier ZIP du dossier sélectionné
             $cheminFichierZIP =  $cheminDossiers .'/'. $dossierSelectionne ;
             print_r($cheminFichierZIP);
             // Vérifier si le fichier ZIP existe
             if (file_exists($cheminFichierZIP)) {
                 // Proposer le téléchargement du fichier ZIP
                 header('Content-Type: application/zip');
                 header('Content-Disposition: attachment; filename="' . basename($cheminFichierZIP) . '"');
                 header('Content-Length: ' . filesize($cheminFichierZIP));
     
                 readfile($cheminFichierZIP);
     
                 exit;
             } else {
                 echo 'Le fichier ZIP n\'existe pas.';
             }
         }
     }
     
        $this->view->render('layout.php', 'downloadFolderArchivZip.php', [
         'title' => 'Téléchargement',
         'message' => '',
         'alert' => $alert,
         'alertValid' => $alertValid,
         'dossiers' => $dossiers
         ]); 

     }
     /* end downloadarchive.zip */
}