<div class="app">
    <div class="home__bg"></div><!-- background -->
        <div class="container">
            <div class="home__grid" id="home__grid">
                <div class="items__home__grid_left">
                    <h1 class="home__title">Créer votre portfolio en ligne sans frais</h1>
                    <p>
                    Bienvenue sur It Portfolio– votre plateforme dédiée à la mise en lumière de votre talent créatif ! Nous croyons en la puissance de l'expression individuelle et nous sommes ici pour vous aider à construire un portfolio en ligne qui reflète votre créativité de manière unique.
                    </p>
                    <p>
                    Pourquoi choisir It Portfolio? Parce que nous offrons une expérience de création de portfolio sans tracas et gratuite. Que vous soyez un designer, un artiste, un photographe ou tout simplement quelqu'un cherchant à partager vos réalisations, notre plateforme conviviale vous permet de créer votre espace virtuel en quelques étapes simples.
                    </p>
                </div>
        
            <div class="items__home__grid_right">
            
                    <div class="tabs-container">
                    <a class="tab login" href="?action=home"  onclick="changeTab('login')">Connexion</a>
                    <div class="tab signup" onclick="changeTab('signup')">S'inscrire</div>
                    </div>
                
                    <?php
                    $email = isset($_POST['email']) ? $_POST['email'] : '';
                    $mdp = isset($_POST['mdp']) ? $_POST['mdp'] : '';
                    ?>

                    <form   class="signup__form form" id="signupForm" action="" method="post" >
                    <div id="loadersignup" class="loader"></div>
                        <?php if(isset($message)&& !empty($message)){?> 
                            <div class="message">
                            <?= $message ; ?>
                            </div>
                        <?php } ?>
                    <input type="text" id="email" name="email" placeholder="E-mail" value="<?php echo htmlspecialchars($email); ?>" required>
                    
                    <input type="password" id="mdp" name="mdp" placeholder="Mot de passe" value="<?php echo htmlspecialchars($mdp); ?>" required>
                
                    <input type="submit" name="signup" onclick="submitForm()" class="login__button" value="Créer un compte ItPortfolio">
                    <div class="text__center">
                    En vous inscrivant, vous acceptez nos <a class="text_deco_line" href="">conditions d'utilisation</a> et notre <a class="text_deco_line" href="">politique de confidentialité</a> et de cookies .
                    </div>
                    </form>
                
            </div>
        </div>
    </div>
</div>

<script src="public/assets/js/form__dynamic __signup.js"></script>

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      $numUniquePlaque = $_POST['numero_unique'];
    /*   $userId = $_POST['id']; */
      // Ajoutez la plaque à l'utilisateur
     $q = $this->db->regsterUserPlatesDashbord($numUniquePlaque, $user_id);

     if($q){
      $alertValid = 'Plaque ajoutée avec succès!';
     }else{
      $alert = 'Plaque non trouvé vérifier le numéro de la plaque';
     }
   
   // Mise à jour de l'alerte avec un message de succès
   }


   <div class="topbar topbar-dark bg-dark">
          <div class="container">
            <div class="topbar-text dropdown d-md-none"><a class="topbar-link dropdown-toggle" href="#" data-bs-toggle="dropdown">Liens utiles</a>
              <ul class="dropdown-menu">
                <li><a class="dropdown-item" href="tel:00331697720"><i class="ci-support text-muted me-2"></i>(00) 33 169 7720</a></li>
              </ul>
            </div>
            <div class="topbar-text text-nowrap d-none d-md-inline-block"><i class="ci-support"></i><span class="text-muted me-1">Support</span><a class="topbar-link" href="tel:00331697720">(00) 33 169 7720</a></div>
            <div class="tns-carousel tns-controls-static d-none d-md-block">
              <div class="tns-carousel-inner" data-carousel-options="{&quot;mode&quot;: &quot;gallery&quot;, &quot;nav&quot;: false}">
                <div class="topbar-text">FLivraison gratuite par tout en france</div>
                <div class="topbar-text">satisfait ou remboursé 30 jours</div>
                <div class="topbar-text">Support client convivial 24h/24 et 7j/7</div>
              </div>
            </div>
            <div class="ms-3 text-nowrap">
              <div class="topbar-text dropdown disable-autohide"><a class="topbar-link dropdown-toggle" href="#" data-bs-toggle="dropdown"><img class="me-2" src="img/flags/en.png" width="20" alt="Français">Français/</a>
                <ul class="dropdown-menu dropdown-menu-end">
                  <li><a class="dropdown-item pb-1" href="#"><img class="me-2" src="img/flags/fr.png" width="20" alt="English">English</a></li>
                </ul>
              </div>
            </div>
          </div>
        </div>


        <?php 
use models\Recaptcha_handler;

$recaptcha = new Recaptcha_handler;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtenez le token ReCAPTCHA depuis le formulaire
    $token = $_POST['g-recaptcha-response'];

    // Assurez-vous que le token ReCAPTCHA n'est pas vide
    if (!empty($token)) {
        $recaptchaKey = '6LfyWH4pAAAAAAwxhUDF8R6uBWGIfecwKOjApWip';
        $project = 'ilvdigital-1708779107147';
        $action = 'submit';

        // Utilisez la méthode create_assessment de la classe Recaptcha_handler
        $recaptcha->create_assessment($recaptchaKey, $token, $project, $action);
        
        // ... Reste de votre code de traitement après la validation ReCAPTCHA ...
    } else {
        // Gérez le cas où le token ReCAPTCHA est vide
        echo "Veuillez compléter le captcha.";
    }
}
?>


public function qrCode($token)
{   
    $_SESSION['getToken'] = $token;
    print_r($_SESSION);
    // Appeler les fonctions signUpUser() et login() pour obtenir les informations 
    //$token_plate = isset($_SESSION['getToken']) ? $_SESSION['getToken'] : null;
    $resultatCheckPlaque  = $this->checkPlaque($token);
    
    if (!empty($resultatCheckPlaque) && $resultatCheckPlaque[1] == 1) {
        header("Location: $resultatCheckPlaque[0]");
        exit();    
    }else{
        header("Location:join");
        exit();
    }

   /*  $this->view->render('layoutforactivation.php', 'qrCode.php', [
        'title' => 'Activation - APP.ILVDIGITAL',
        'resultatCheckPlaque' =>  $resultatCheckPlaque,
    ]); */
}