<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

if (
    isset($_SERVER['PHP_AUTH_USER']) &&
    isset($_SERVER['PHP_AUTH_PW']) &&
    password_verify($_SERVER['PHP_AUTH_PW'], '$2y$10$1SCxUQhV5cq.yShno5usNuONbq4Hi10ZpeBKSoLQ/N/vHWYDNfNfe')
){
    // Code pour afficher un message d'erreur ou rediriger
    header('WWW-Authenticate: Basic realm="Restricted Area"');
    header('HTTP/1.0 401 Unauthorized');
    echo 'Access Denied';
    exit;
}
// Inclure l'autoloader de Composer
require 'vendor/autoload.php';
// Informations de connexion à la base de données
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "portfoliopourtous";
/* $servername = "db5015232171.hosting-data.io";
$username = "dbu5459977";
$password = "Rk63e51b47af@?227";
$dbname = "dbs12569527"; */
// Connexion à la base de données
$conn = new mysqli($servername, $username, $password, $dbname);
// Vérification de la connexion à la base de données
if ($conn->connect_error) {
    die("La connexion à la base de données a échoué : " . $conn->connect_error);
}
// Vérifier si le formulaire est soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupérer les données du formulaire
    $nombrePlaques = $_POST["nombre_plaques"];
    $categorie = $_POST["categorie"];
    $destinationFolder = './imgqrcode';
    

    // Préparation de la requête SQL
    $query = "INSERT INTO plaques (numero_unique, lien_plaque, plaque_active, activation_token, user_id, category_id, etat_plaque, qr_code) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    $statement = $conn->prepare($query);
    
    // Générer et insérer les plaques
    for ($i = 0; $i < $nombrePlaques; $i++) {
        $lastInsertedId = 0;
        //$numeroUnique = generateNumeroUnique();
        $activationToken = generateActivationToken();
        $lienPlaque = "https://app.ilvdigital.fr/?action=activation&token=$activationToken";
        $qrCode = '';
        
        $maxIterations = 1000;
        $iterationCount = 0;
        
        do {
            $numeroUnique = generateNumeroUnique();
            $iterationCount++;
            if ($iterationCount > $maxIterations) {
                // Gérer le cas où la limite d'itérations est atteinte
                die("Erreur : Impossible de générer un numéro unique non duplicata après $maxIterations itérations.");
            }
        } while (checkDuplicateNumeroUnique($conn, $numeroUnique));
        
        $statement->bind_param("ssisiiis", $numeroUnique, $lienPlaque, $plaqueActive, $activationToken, $userId, $categoryId, $etatPlaque, $qrCode);

        // Définir les valeurs pour chaque paramètre
        $plaqueActive = 0;
        $userId = null;
        $categoryId = getCategoryID($conn, $categorie);
        $etatPlaque = 0;

        // Exécution de la requête
        $statement->execute();
        
        $lastInsertedId = $conn->insert_id; 

         // Générer et sauvegarder le QR code après avoir récupéré l'ID
        $qrCode = generateAndSaveLinkQrCode($lienPlaque, $numeroUnique, $destinationFolder, $lastInsertedId,$categoryId);
        
        // Mettez à jour le champ qr_code dans la base de données avec le chemin du fichier
        $statementUpdate = $conn->prepare("UPDATE plaques SET qr_code = ? WHERE id = ?");
        $statementUpdate->bind_param("si", $qrCode, $lastInsertedId);
        $statementUpdate->execute();
        $statementUpdate->close();
    } 
    $statement->close();
    echo "Commande réussie !";

}
// Fonction pour générer un numéro unique avec une longueur spécifique
function generateNumeroUnique($length = 7) {
    // Générer une chaîne de caractères aléatoires
    $randomString = bin2hex(random_bytes($length));

    // Retourner les premiers caractères de la chaîne jusqu'à la longueur spécifiée
    return substr($randomString, 0, $length);
}


// Fonction pour générer un token
function generateActivationToken() {
    return bin2hex(random_bytes(16));
}

// Fonction pour générer un QR code pour un lien et l'enregistrer dans un fichier
function generateAndSaveLinkQrCode($link, $numeroUniquePlaque, $destinationFolder, $lastInsertedId,$type_plate) {

    $backgroundImagePath ='./background_qr_code/'.$type_plate.'.png';

    // Utilisation de la classe appropriée de BaconQrCode
    $qrCodeRenderer = new BaconQrCode\Renderer\Image\Png();
    $qrCodeRenderer->setMargin(0);
    $qrCodeRenderer->setHeight(260);
    $qrCodeRenderer->setWidth(260);

    $writer = new BaconQrCode\Writer($qrCodeRenderer);
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
    imagecopy($backgroundImage,$qrCodeImage,581,500,0,0, imagesx( $qrCodeImage), imagesy($qrCodeImage));

    // Ajouter le numéro unique comme texte sur l'image
    $textColor = imagecolorallocate($backgroundImage, 0, 0, 0); // Couleur du texte en noir
    $fontSize = 20;
    $fontPath = './Roboto_Condensed/RobotoCondensed-VariableFont_wght.ttf'; // Remplacez par le chemin de votre police TrueType
    $x = 40;
    $y = imagesy($backgroundImage) - 30;
    imagettftext($backgroundImage, $fontSize, 0, $x, $y, $textColor, $fontPath, $lastInsertedId);


    // Nom du fichier QR code basé sur le numéro unique de la plaque
    $filename = $lastInsertedId.'_'.$numeroUniquePlaque . '_qr_code.png';

    // Construire le chemin complet du fichier de destination
    $destinationPath = $destinationFolder . '/' . $filename;

    // Enregistrer le QR code dans le fichier spécifié
    file_put_contents($destinationPath, $qrCodeImage);
    
    // Enregistrer l'image finale dans le fichier spécifié
    imagepng($backgroundImage, $destinationPath);
    // Convertir l'image en PDF
    $destinationFolderPdf =__DIR__ .'/qrCodePDF';
    $pdfFilename = $destinationFolderPdf . '/' . $lastInsertedId . '_' . $numeroUniquePlaque . '_qr_code.pdf';
    generateSinglePDF($backgroundImage, $pdfFilename);
    // Libérer la mémoire
    imagedestroy($qrCodeImage);
    imagedestroy($backgroundImage);
    

    return $destinationPath;
}

// Fonction pour générer un PDF à partir d'une image
function generateSinglePDF($imageResource, $pdfFilename) {
    // Enregistrer l'image temporairement
    $tempImageFile = tempnam(sys_get_temp_dir(), 'qr_code');
    imagepng($imageResource, $tempImageFile);

    // Inclure l'autoloader de TCPDF
    require_once('vendor/autoload.php');

    // Créer une nouvelle instance de TCPDF
    $pdf = new TCPDF();
    // Set margin (optional, but can be useful to ensure there's space for your content)
    $pdf->SetMargins(10, 10, 10);

    // Disable header and footer
    $pdf->SetPrintHeader(false);
    $pdf->SetPrintFooter(false);
    // Ajouter une page au PDF
    $pdf->AddPage();

    // Ajouter l'image au PDF
    $pdf->Image($tempImageFile, 10, 10, 50, 50);

    // Enregistrer le PDF dans le dossier PDF spécifié
    //$pdfFolder = './qrCodePDF'; // Remplacez par le chemin de votre dossier
    //$pdfPath = $pdfFolder . '/' . $pdfFilename;
    $pdf->Output($pdfFilename, 'F');

    return $pdfFilename;
}


function getCategoryID($con, $categoryName) {
    $categoryId = null;
    $stmt = $con->prepare("SELECT id FROM category_plaque WHERE category_name = ?");
    $stmt->bind_param("s", $categoryName);

    // Exécutez la requête
    $stmt->execute();

    // Liez le résultat à une variable
    $stmt->bind_result($categoryId);

    // Fetch le résultat
    $stmt->fetch();

    // Fermez le statement
    $stmt->close();

    return $categoryId;
}

function checkDuplicateNumeroUnique($con, $numeroUnique) {
    $stmt = $con->prepare("SELECT 1 FROM plaques WHERE numero_unique = ? LIMIT 1");
    $stmt->bind_param("s", $numeroUnique);
    $stmt->execute();
    $stmt->store_result();
    $result = $stmt->num_rows > 0;
    $stmt->close();
    return $result;
}

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Commande de Plaques</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="public/assets/css/style.css">
</head>
<body>
<div class="container">
    <h2>Commande de Plaques</h2>
    <form action="" method="post">
        <label for="nombre_plaques">Nombre de plaques :</label>
        <input type="number" name="nombre_plaques" required>
        <label for="categorie">Catégorie :</label>
        <select name="categorie" required>
            <option value="avis google">Avis Google</option>
            <option value="tiktok">TikTok</option>
            <option value="instagram">Instagram</option>
            <option value="wifi">WiFi</option>
            <option value="e-mail">E-mail</option>
        </select>
        <button type="submit">Commander</button>
    </form>

    <h2>Liste des Plaques</h2>
<!-- Formulaire de recherche par numéro unique -->
    <form action="" method="get">
        <label for="search">Recherche par numéro unique :</label>
        <input type="text" name="search" id="search" placeholder="Entrez le numéro unique">
        <button type="submit">Rechercher</button>
    </form>
<?php  

// Paramètres de pagination
$records_par_page = 10; // Nombre d'enregistrements par page
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? $_GET['page'] : 1; // Numéro de la page par défaut

// Calcul de l'offset pour la requête SQL
$offset = ($page - 1) * $records_par_page;
// Vérifier si la recherche est soumise
if (isset($_GET['search'])) {
    $search_term = $_GET['search'];

    // Requête SQL pour la recherche par numéro unique
    $sql_search = "SELECT * FROM plaques WHERE numero_unique LIKE '%$search_term%' LIMIT $offset, $records_par_page";
    $result_search = $conn->query($sql_search);

    // Affichage des résultats dans un tableau
    echo '<table class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Numero Unique</th>
                    <th>Lien Plaque</th>
                    <th>Etat Plaque</th>
                </tr>
            </thead>
            <tbody>';

    if ($result_search->num_rows > 0) {
        while ($row_search = $result_search->fetch_assoc()) {
            echo '<tr>
                    <td>' . $row_search['id'] . '</td>
                    <td>' . $row_search['numero_unique'] . '</td>
                    <td>' . $row_search['lien_plaque'] . '</td>
                    <td>' . $row_search['etat_plaque'] . '</td>
                </tr>';
        }
    } else {
        echo '<tr><td colspan="4">Aucun résultat trouvé</td></tr>';
    }

    echo '</tbody></table>';

    // Affichage des liens de pagination pour les résultats de recherche
    $sql_pagination_search = "SELECT COUNT(*) as total FROM plaques WHERE numero_unique LIKE '%$search_term%'";
    $result_pagination_search = $conn->query($sql_pagination_search);
    $row_pagination_search = $result_pagination_search->fetch_assoc();
    $total_records_search = $row_pagination_search['total'];
    $total_pages_search = ceil($total_records_search / $records_par_page);

    echo '<nav aria-label="Page navigation">
            <ul class="pagination">';

    for ($i = 1; $i <= $total_pages_search; $i++) {
        echo '<li class="page-item ' . ($i == $page ? 'active' : '') . '">
                <a class="page-link" href="?page=' . $i . '&search=' . $search_term . '">' . $i . '</a>
            </li>';
    }

    echo '</ul></nav>';
}

// Fermeture de la connexion à la base de données
// Requête SQL pour récupérer les enregistrements avec pagination
$sql = "SELECT * FROM plaques LIMIT $offset, $records_par_page";
$result = $conn->query($sql);

// Affichage des résultats dans un tableau
echo '<table class="table table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Numero Unique</th>
                <th>Lien Plaque</th>
                <th>etat</th>
                <th>Utilisateur</th>
                <th>Usage Plapue</th>
                <th>Qr code</th>
            </tr>
        </thead>
        <tbody>';

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo '<tr>
                <td>' . $row['id'] . '</td>
                <td>' . $row['numero_unique'] . '</td>
                <td>' . $row['lien_plaque'] . '</td>
                <td>' . $row['plaque_active'] . '</td>
                <td>' . $row['user_id'] . '</td>
                <td>' . getCategoryPlaque($conn,$row['category_id']) . '</td>
                <td> <img src='.$row['qr_code'].' whith="50" height="50"/> </td>
            </tr>';
    }
} else {
    echo '<tr><td colspan="4">Aucun enregistrement trouvé</td></tr>';
}

echo '</tbody></table>';

/* Catégory fonction */

function getCategoryPlaque($conn, $id) {
    $categoryName = "";
    $sql = "SELECT category_name FROM category_plaque WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id); // "i" indique que c'est un entier (integer)
    $stmt->execute();
    $stmt->bind_result($categoryName); // Lier le résultat à une variable

    // Fetch le résultat
    $stmt->fetch();

    // Fermer le statement
    $stmt->close();

    return $categoryName;
}

/* end category fonction */

// Affichage des liens de pagination
$sql_pagination = "SELECT COUNT(*) as total FROM plaques";
$result_pagination = $conn->query($sql_pagination);
$row_pagination = $result_pagination->fetch_assoc();
$total_records = $row_pagination['total'];
$total_pages = ceil($total_records / $records_par_page);

echo '<nav aria-label="Page navigation">
        <ul class="pagination">';

for ($i = 1; $i <= $total_pages; $i++) {
    echo '<li class="page-item ' . ($i == $page ? 'active' : '') . '">
            <a class="page-link" href="?page=' . $i . '">' . $i . '</a>
        </li>';
}

echo '</ul></nav>';

// Fermeture de la connexion à la base de données
$conn->close();
?>


</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js"></script>
</body>
</html>


