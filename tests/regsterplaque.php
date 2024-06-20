<?php 
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "portfoliopourtous";

$conn = new mysqli($servername, $username, $password, $dbname);

// Vérification de la connexion à la base de données
if ($conn->connect_error) {
    die("La connexion à la base de données a échoué : " . $conn->connect_error);
}


function newPlaque($numero_unique)
{

    global $conn ;
    // Génération d'un jeton d'activation unique
    $activation_token = bin2hex(random_bytes(16));
    $link_redirect = "http://localhost/Protfoliopourtous/?action=emailsignup&plq=$numero_unique&token=$activation_token";
    $user_id = null; 
    $active = 0;
    // Enregistrement du jeton dans la base de données
    try {
        $query = "INSERT INTO plaques (numero_unique, lien_plaque, plaque_active, user_id, activation_token) VALUES (?, ?, ?, ?, ?)";
        $statement = $conn->prepare($query);
        $statement->bind_param('ssiss', $numero_unique, $link_redirect, $active, $user_id, $activation_token);
       
        $statement->execute();
        echo 'ok';
    } catch (PDOException $e) {
        die("Erreur d'insertion : " . $e->getMessage());
    }

}
$numero_unique = "PLQ0008" ;
newPlaque($numero_unique);

?>
<script>
var menu = document.getElementById("site-header");
var logos = document.getElementsByClassName("custom-logo");
var menuLink =  document.querySelectorAll(".menu-link") ;
var lastScrollTop = 0;

window.addEventListener("scroll", function() {
    var currentScroll = window.scrollY;

    if (currentScroll > lastScrollTop) {
        // L'utilisateur fait défiler vers le bas
        menu.classList.remove("sticky");
        menu.style.backgroundColor = "rgba(0,0,0,0.0)";

        // Parcourez tous les éléments de la collection logo et définissez la hauteur minimale
        for (var i = 0; i < logos.length; i++) {
            logos[i].style.minHeight = "55px";
        }

        for (var i = 0; i < menuLink.length; i++) {
            menuLink[i].style.setProperty("color", "#FFF", "important");
        }
    } else {
        // L'utilisateur fait défiler vers le haut
        if (currentScroll <= 50) {
            menu.classList.remove("sticky");
            menu.style.backgroundColor = "rgba(0,0,0,0.0)";
        } else {
            menu.classList.add("sticky");
            menu.style.setProperty("background-color", "rgba(0, 0, 0, 0.7)", "important");
            
        }

        // Parcourez tous les éléments de la collection logo et définissez la hauteur minimale
        for (var i = 0; i < logos.length; i++) {
            logos[i].style.setProperty("min-height", "35px", "important");
        }
        for (var i = 0; i < menuLink.length; i++) {
            menuLink[i].style.setProperty("color", "#482BC9", "important");
        }
    }

    lastScrollTop = currentScroll;
});


var menu = document.getElementById("site-header");
var logos = document.getElementsByClassName("custom-logo");
var menuLink =  document.querySelectorAll("#menu-barre-de-navigation-principal>.menu-item > a") ;
var iconNav = document.querySelector("#site-navigation-wrap  .dropdown-menu > li > a");
var lastScrollTop = 0;

window.addEventListener("scroll", function() {
    var currentScroll = window.scrollY;

    if (currentScroll > lastScrollTop) {
        // L'utilisateur fait défiler vers le bas
        menu.classList.remove("sticky");
        menu.style.backgroundColor = "rgba(0,0,0,0.0)";

        // Parcourez tous les éléments de la collection logo et définissez la hauteur minimale
        for (var i = 0; i < logos.length; i++) {
            logos[i].style.minHeight = "55px";
        }
  for (var i = 0; i < menuLink.length; i++) {
            menuLink[i].style.setProperty("color", "#FFF", "important");
        }
    } else {
        // L'utilisateur fait défiler vers le haut
        if (currentScroll <= 50) {
            menu.classList.remove("sticky");
            menu.style.backgroundColor = "rgba(0,0,0,0.0)";
    for (var i = 0; i < menuLink.length; i++) {
            menuLink[i].style.setProperty("color", "#FFF", "important");
        }
        } else {
            menu.classList.add("sticky");
            menu.style.setProperty("background-color", "rgba(0, 0, 0, 0.7)", "important");
             for (var i = 0; i < menuLink.length; i++) {
            menuLink[i].style.setProperty("color", "#482BC9", "important");
                }
            iconNav.style.setProperty("color", "#482BC9" , "important");  
        }

        // Parcourez tous les éléments de la collection logo et définissez la hauteur minimale
        for (var i = 0; i < logos.length; i++) {
            logos[i].style.setProperty("min-height", "35px", "important");
        }

    }

    lastScrollTop = currentScroll;
});
</script>