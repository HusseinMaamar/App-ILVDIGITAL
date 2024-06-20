<?php

// Vérifier si le terme de recherche est présent dans la requête POST
if (isset($_POST['input'])) {
    // Récupérer le terme de recherche depuis la requête POST
    $input = $_POST['input'];

    // Appeler la fonction pour effectuer une recherche d'autocomplétion
    $suggestions = autocomplete($input);

    // Renvoyer les suggestions au format JSON
    echo json_encode($suggestions);
}

// Fonction pour effectuer une recherche d'autocomplétion à l'aide de l'API Google Places
function autocomplete($input) {
    // Remplacez "VOTRE_CLE_API" par votre propre clé API Google Places
    $apiKey = "AIzaSyAEhp5urXT89jNctF4p28teubC80JwNwus";
    $url = "https://maps.googleapis.com/maps/api/place/autocomplete/json?input=" . urlencode($input) . "&language=fr&types=establishment&key=" . $apiKey;

    // Effectuer la requête à l'API Google Places
    $response = file_get_contents($url);

    // Analyser la réponse JSON
    $data = json_decode($response, true);

    // Vérifier si l'API a renvoyé des résultats valides
    if (isset($data['predictions'])) {
        // Extraire les descriptions des suggestions
        $suggestions = array_map(function($prediction) {
            return array('description' => $prediction['description']);
        }, $data['predictions']);

        return $suggestions;
    } else {
        return array(); // Renvoyer un tableau vide si aucune suggestion n'est disponible
    }
}

?>
