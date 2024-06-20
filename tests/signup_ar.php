
<?php
$c = new controller\PlatesController;
 session_start();
 $activation_token = isset($_GET["token"]) ? $_GET["token"] : "";
 $_SESSION['getToken'] = $activation_token;

 $resultatCheckPlaque  = $c->checkPlaque($activation_token);
 print_r($activation_token );
?>
<?php  if(!empty($resultatCheckPlaque) && $resultatCheckPlaque[0] !== "https://app.ilvdigital.fr/?action=activation&token=$$activation_token" && $resultatCheckPlaque[1] == 1) { ?>

    <?php header("Location: $resultatCheckPlaque[0]"); 
       exit();
    ?>
 
<?php }else{  ?>
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
                    
                     <?php //$numUniquePlaque = $_GET['plq']; ?>
                    <!-- <input type="hidden" name="plq" value="<?= $numUniquePlaque  ; ?>"> -->

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

<?php } ?>