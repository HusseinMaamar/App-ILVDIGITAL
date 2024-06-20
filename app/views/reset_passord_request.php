<!-- reset_password_request.php -->
<div class="container py-4 py-lg-5 my-4">
        <div class="row justify-content-center">
          <div class="col-lg-8 col-md-10">
             <!-- alert -->
             <?php if (!empty($alert)): ?>
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <?= $alert; ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                            <?php elseif (isset($alertValid) && !empty($alertValid)): ?>
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <?= $alertValid; ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                <?php endif ?>
                
            <h2 class="h3 mb-4">Mot de passe oublié?</h2>
            <p class="fs-md">Changez votre mot de passe en trois étapes simples. Cela permet de sécuriser votre nouveau mot de passe.</p>
            <ol class="list-unstyled fs-md">
              <li><span class="text-primary me-2">1.</span>Remplissez votre adresse e-mail ci-dessous.</li>
              <li><span class="text-primary me-2">2.</span>Nous vous enverrons par e-mail un lien temporaire.</li>
              <li><span class="text-primary me-2">3.</span>Utilisez le lein pour changer votre mot de passe.</li>
            </ol>
            <div class="card py-2 mt-4">
              <form class="card-body needs-validation" novalidate  action="" method="post">
                <div class="mb-3">
                  <label class="form-label" for="recover-email">Entrez votre adresse email</label>
                  <input class="form-control" type="email" id="recover-email" name="email" required>
                  <div class="invalid-feedback">Adresse email requise*.</div>
                </div>
                <input  class="btn btn-primary" type="submit" name="account-password-recovery" value="Obtenir un nouveau mot de passe">
              </form>
            </div>
          </div>
    </div>
</div>
