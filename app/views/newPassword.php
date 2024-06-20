<!-- reset_password_confirm.php -->
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

            <div class="card py-2 mt-4">
            <h2 class="h3 text-center mb-4">Nouveau mot de passe</h2>

            <?php if(isset($message)&& !empty($message)){?> 
            <div class="text-danger text-center">
             <p class='fs-ms'> <?= $message; ?> </p>
             </div>
             <?php } ?>

              <form action="" method="post" class="card-body needs-validation" novalidate>
                <div class="mb-3">
                <label class="form-label" for="si-password">Mot de passe *</label>
                <div class="password-toggle">
                  <input class="form-control" type="password" id="si-password" name="password" required>
                  <label class="password-toggle-btn" aria-label="Show/hide password">
                   <input class="password-toggle-check" type="checkbox"><span class="password-toggle-indicator"></span>
                </label>
                </div>

                <label class="form-label" for="si-passwordConfirm">Confirmation du mot de passe *</label>
                <div class="password-toggle">
                  <input class="form-control" type="password" id="si-passwordConfirm" name="passwordConfirm" required>
                  <label class="password-toggle-btn" aria-label="Show/hide password">
                   <input class="password-toggle-check" type="checkbox"><span class="password-toggle-indicator"></span>
                </label>
                </div>
                <input type="hidden" name="token" value="<?php echo $_GET['token']; ?>">
                </div>
                <button class="btn btn-primary w-100" type="submit">Valider</button>
              </form>
            </div>
          </div>
        </div>
      </div>