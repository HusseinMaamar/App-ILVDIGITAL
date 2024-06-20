<?php
phpinfo();

$user_id = $_SESSION['user']['id'] ;
print_r($user_id);
// Connexion à la base de données (à personnaliser avec vos propres informations)
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "portfoliopourtous";

/* $servername = "db5015232171.hosting-data.io";
$username = "dbu5459977";
$password = "Rk63e51b47af@?227";
$dbname = "dbs12569527"; */

$conn = new mysqli($servername, $username, $password, $dbname);

// Vérification de la connexion à la base de données
if ($conn->connect_error) {
    die("La connexion à la base de données a échoué : " . $conn->connect_error);
}

function numunique($user_id) {
    global $conn;

    $stmt = $conn->prepare("SELECT numero_unique FROM plaques WHERE user_id = ? AND plaque_active = 1");
    $stmt->bind_param("s", $user_id);
    $stmt->execute();
    $stmt->bind_result($numero_plaque);
    $stmt->fetch();
    $stmt->close();

    return $numero_plaque;
}

// Traitement de la requête GET pour obtenir le numéro unique
$numero_unique = numunique($user_id);

function obtenirLienRedirection($numero_unique) {
    global $conn;

    $stmt = $conn->prepare("SELECT lien_plaque FROM plaques WHERE numero_unique = ? AND plaque_active = 1");
    $stmt->bind_param("s", $numero_unique);
    $stmt->execute();
    $stmt->bind_result($lien_redirection);
    $stmt->fetch();
    $stmt->close();

    return $lien_redirection;
}



// Vérification de l'activation de la plaque
$lien_redirection = obtenirLienRedirection($numero_unique);


// Traitement du formulaire de modification du lien après l'activation
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $numero_unique = $_POST["numero_unique"];
    $nouveau_lien = $_POST["nouveau_lien"];

    // Validation des données (à personnaliser selon vos besoins)
    if (empty($numero_unique) || empty($nouveau_lien)) {
        echo "Veuillez remplir tous les champs.";
    } else {
        // Fonction de mise à jour du lien de la plaque après l'activation
        mettreAJourLienPlaque($numero_unique, $nouveau_lien);
    }
}

// Fonction d'activation de la plaque
// Fonction de mise à jour du lien de la plaque après l'activation
function mettreAJourLienPlaque($numero_unique, $nouveau_lien) {
    global $conn;

    $stmt = $conn->prepare("UPDATE plaques SET lien_avis_google = ? WHERE numero_unique = ?");
    $stmt->bind_param("ss", $nouveau_lien, $numero_unique);

    if ($stmt->execute()) {
        echo "Lien de la plaque mis à jour avec succès!";
    } else {
        echo "Erreur lors de la mise à jour du lien de la plaque : " . $stmt->error;
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulaire d'activation de plaque</title>
</head>
<body>
<?php  if(!empty($lien_redirection)) { ?>
    <h2>Formulaire d'activation de plaque</h2>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <label for="numero_unique">Numéro unique de la plaque:</label>
        <input type="hidden" name="numero_unique" value="<?= $numero_plaque ; ?>" required><br>
        <label for="lien_avis_google">Lien pour laisser un avis Google:</label>
        <input type="text" name="nouveau_lien" required><br>
        <input type="submit" value="Activer la plaque">
    </form>
<?php }else{  
    header("Location: $lien_redirection");
    exit();
} ?>
</body>
</html>
<?php
// Fermer la connexion à la base de données
$conn->close();
?>


