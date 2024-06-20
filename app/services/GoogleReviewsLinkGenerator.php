<?php
namespace services;

class GoogleReviewsLinkGenerator {
    private $apiKey;

    // Constructeur de la classe prenant la clé API Google Places comme paramètre
    public function __construct($apiKey) {
        $this->apiKey = $apiKey;
    }

    // Méthode pour récupérer l'identifiant unique de l'établissement à partir de son nom
    private function getPlaceId($nomEtablissement) {
        $url = "https://maps.googleapis.com/maps/api/place/findplacefromtext/json?input=" . urlencode($nomEtablissement) . "&inputtype=textquery&fields=place_id&key=" . $this->apiKey;
        $response = file_get_contents($url);
        $data = json_decode($response, true);
        if (isset($data['candidates']) && count($data['candidates']) > 0) {
            return $data['candidates'][0]['place_id'];
        } else {
            return null;
        }
    }

    // Méthode pour générer le lien vers les avis Google
    public function genererLienAvis($nomEtablissement) {
        $placeId = $this->getPlaceId($nomEtablissement);
        if ($placeId) {
            $avisUrl = "https://search.google.com/local/writereview?placeid=" . $placeId;
            return  $avisUrl;
        } else {
            return "Aucun établissement trouvé.";
        }
    }
}


?>
