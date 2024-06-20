
<?php
// Initialiser les variables avec les valeurs par défaut ou vides
$email = isset($_POST['email']) ? $_POST['email'] : '';
$mdp = isset($_POST['mdp']) ? $_POST['mdp'] : '';
?>



<div class="tabs-container">
        <div class="tab login" onclick="changeTab('login')">Connexion</div>
        <a class="tab signup" href="?action=activation"  onclick="changeTab('signup')">S'inscrire</a>
</div>

<form class="login__form form" id="loginForm" action="" method="post" >
    <div id="loaderlogin" class="loader"></div>
    <?php if(isset($message)&& !empty($message)){?> 
        <div class="message" >
            <?= $message ; ?>
        </div>
    <?php } ?>
    <div class='message' id="messagejs">

    </div>
    <input type="email" id="email" name="email" placeholder="E-mail"  value="<?php echo htmlspecialchars($email); ?>" required>
    

    <input type="password" id="mdp" name="mdp" placeholder="Mot de passe" value="<?php echo htmlspecialchars($mdp); ?>" required>
     <div class="text__center"><a class="text_deco_line" href="?action=passwordreset">Mot de passe oublié ?</a></div>
    <!-- Bouton de soumission -->
    <input type="submit" name="login" onclick="validateformAndLoader()" class="login__button" value="Connexion">
</form>



<script src="public/assets/js/form__dynamic.js"></script>