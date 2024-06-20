<?php
// Composer autoload
require 'vendor/autoload.php';

// Fonction pour générer un QR code Wi-Fi
function genererQrCodeWifi($ssid, $mot_de_passe, $type_encryption = "WPA") {
    $data = "WIFI:T:$type_encryption;S:$ssid;P:$mot_de_passe;;";
    
    // Utilisation de la classe appropriée de BaconQrCode
    $qrCodeWifi = new BaconQrCode\Renderer\Image\Png();
    $qrCodeWifi->setMargin(0);
    $qrCodeWifi->setHeight(300);
    $qrCodeWifi->setWidth(300);

    $writer = new BaconQrCode\Writer($qrCodeWifi);
    $qrCodeImage = $writer->writeString($data);

    return $qrCodeImage;
}

// Informations pour la connexion Wi-Fi
$ssid = "Neptune-FI-WI";
$mot_de_passe = "flex75000";

// Générer le QR code Wi-Fi
$qrCodeWifiImage = genererQrCodeWifi($ssid, $mot_de_passe);

// Afficher le QR code
header('Content-Type: image/png');
echo $qrCodeWifiImage;
?>
