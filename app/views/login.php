<form class="needs-validation tab-pane fade" autocomplete="off" novalidate id="signin-tab" action="" method="post" >
    <div id="loaderlogin" class="loader"></div>
    <div class="mb-3">
    <label class="form-label" for="si-email">Email</label>
    <input class="form-control" type="email" id="si-email" name="email" placeholder="Email"  value="<?php echo htmlspecialchars($email); ?>" required>
    <div class="invalid-feedback">Email requis *</div>
    </div>
    <div class="mb-3">
        <label class="form-label" for="si-password">Mot de passe</label>
        <div class="password-toggle">
            <input class="form-control" type="password" id="si-password" name="mdp" placeholder="Mot de passe" value="<?php echo htmlspecialchars($mdp); ?>" required>
          <label class="password-toggle-btn" aria-label="Show/hide password">
          <input class="password-toggle-check" type="checkbox"><span class="password-toggle-indicator"></span>
          </label>
         </div>
        </div>
        <a class="nav-link-inline text-accent fs-sm" href="ForgotPassword" target="_blank">Mot de passe oublié?</a>
    <hr class="mt-4">
    <!-- Bouton de soumission -->
    <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
    <input class="btn btn-accent btn-shadow d-block w-100" type="submit" name="login"  value="Se connecter">
</form>