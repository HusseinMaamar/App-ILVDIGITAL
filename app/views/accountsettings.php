
<div class="d-sm-flex flex-wrap justify-content-between align-items-center border-bottom">
        <h2 class="h3 py-2 me-2 text-center text-sm-start">Paramètres</h2>
    </div>
              <div class="row gx-4 gy-3">
                  <form  action="" method="post">
                      <div class="col-sm-6">
                        <label class="form-label" for="dashboard-fn">Prénom et Nom</label>
                        <input class="form-control" type="text" id="dashboard-fn" name="userName" value="<?= $_SESSION['user']['userName'] ;?>">
                      </div>

                      <div class="col-sm-6">
                        <label class="form-label" for="dashboard-email">Email</label>
                        <input class="form-control" type="text" id="dashboard-email" value="<?= $_SESSION['user']['email'] ;?>" disabled>
                      </div>

      
                      <div class="col-sm-6">
                        <label class="form-label" for="dashboard-address">Mot de passe</label>
                        <input class="form-control" type="password" name="password" id="dashboard-address" >
                      </div>
                      
                      <div class="col-12">
                        <hr class="mt-4 mb-4">
                        <div class="d-sm-flex justify-content-between align-items-center">
                          <input class="btn btn-primary mt-3 mt-sm-0" name="updateAccount" type="submit" value="Valider">
                        </div>
                      </div>
                 </form>
              </div>