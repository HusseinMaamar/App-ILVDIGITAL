<form class="needs-validation tab-pane fade show active" autocomplete="off" novalidate id="signup-tab" action="" method="post">
    <div id="loadersignup" class="loader"></div>
    <div class="mb-3">
        <label class="form-label" for="su-name">Nom et Prénom</label>
        <input class="form-control" type="text" id="su-name" name="user_name" placeholder="Nom et Prénom" value="<?php echo htmlspecialchars($name); ?>"  required>
        <div class="invalid-feedback">Nom et Prénom</div>
    </div>
    <div class="mb-3">
        <label for="su-email">Email address</label>
        <input class="form-control" type="email" id="su-email" name="email" placeholder="johndoe@exemple.com" value="<?php echo htmlspecialchars($email); ?>" required>
        <div class="invalid-feedback">Entrez une adresse email valide.</div>
    </div>
    <div class="mb-3">
        <label class="form-label" for="su-password">Password</label>
        <div class="password-toggle">
            <input class="form-control" type="password" id="su-password" name="mdp" placeholder="Mot de passe" value="<?php echo htmlspecialchars($mdp); ?>" required>
            <label class="password-toggle-btn" aria-label="Show/hide password">
                <input class="password-toggle-check" type="checkbox"><span class="password-toggle-indicator"></span>
            </label>
        </div>
    </div>
    <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
   
    <input type="submit" name="signup" class="btn btn-accent btn-shadow d-block w-100" value="S'inscrire">
      
    <!-- <div class="text-center mt-4 fs-ms">
        En vous inscrivant, vous acceptez nos <a class="fw-semibold text-dark" href="">conditions d'utilisation</a> et notre <a class="fw-semibold text-dark" href="">politique de confidentialité</a> et de cookies.
    </div> -->
</form>
