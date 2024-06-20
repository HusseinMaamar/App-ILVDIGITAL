<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Générer le lien vers les avis Google</title>
</head>
<body>
    <h1>Générer le lien vers les avis Google</h1>
    <form method="post" action="">
        <label for="nomEtablissement">Nom de l'établissement :</label>
        <input type="text" id="nomEtablissement" name="nomEtablissement" required>
        <div id="suggestions"></div>
        <button type="submit">Générer le lien</button>
    </form>

    <?php
    // Vérifier si le formulaire a été soumis
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Vérifier si le champ nomEtablissement n'est pas vide
        if (!empty($_POST["nomEtablissement"])) {
            // Récupérer le nom de l'établissement saisi par l'utilisateur
            $nomEtablissement = $_POST["nomEtablissement"];
            $predictions = autocomplete($nomEtablissement);
            echo "<ul>";
            foreach ($predictions as $prediction) {
                echo "<li>" . $prediction['description'] . "</li>";
            }
            echo "</ul>";
            // Appeler la fonction pour générer le lien vers les avis Google
            echo genererLienAvis($nomEtablissement);
        } else {
            echo "Veuillez saisir le nom de l'établissement.";
        }
    }
    
    // Fonction pour récupérer l'identifiant unique de l'établissement à partir de son nom
    function getPlaceId($nomEtablissement) {
        // Ajoutez votre propre clé API Google Places
        $apiKey = "AIzaSyAEhp5urXT89jNctF4p28teubC80JwNwus";
        $url = "https://maps.googleapis.com/maps/api/place/findplacefromtext/json?input=" . urlencode($nomEtablissement) . "&inputtype=textquery&fields=place_id&key=" . $apiKey;

        // Effectuer la requête à l'API Google Places
        $response = file_get_contents($url);

        // Analyser la réponse JSON
        $data = json_decode($response, true);

        // Vérifier si l'API a renvoyé des résultats valides
        if (isset($data['candidates']) && count($data['candidates']) > 0) {
            return $data['candidates'][0]['place_id'];
        } else {
            return null;
        }
    }

    // Fonction pour générer le lien vers les avis Google
    function genererLienAvis($nomEtablissement) {
        $placeId = getPlaceId($nomEtablissement);
        if ($placeId) {
            $avisUrl = "https://search.google.com/local/writereview?placeid=" . $placeId;
            return "<a href='" . $avisUrl . "' target='_blank'>Lien vers les avis Google</a>";
        } else {
            return "Aucun établissement trouvé.";
        }
    }


    function autocomplete($input) {
        $apiKey = "AIzaSyAEhp5urXT89jNctF4p28teubC80JwNwus";
        $url = "https://maps.googleapis.com/maps/api/place/autocomplete/json?input=" . urlencode($input) . "&types=establishment&key=" . $apiKey;
        $response = file_get_contents($url);
        $data = json_decode($response, true);
        return $data['predictions'];
    }

 
    ?>



</body>
</html>
