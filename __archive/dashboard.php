<?php  
use models\CategoryPlatesModel;
$category = new CategoryPlatesModel;
$userController = new controller\UserController;
$HtmlGenerate = new functions\HtmlGenerate;
?>

<?php if($userController->admin()){?>
     <!-- Title-->
     <div class="d-sm-flex flex-wrap justify-content-between align-items-center border-bottom">
        <h2 class="h3 py-2 me-2 text-center text-sm-start">Commandes de plaques<span class="badge bg-faded-accent fs-m text-body align-middle ms-2">Gestion</span></h2>
    </div>
    <form action="" method="post">
        <label class="form-label" for="nombre_plaques">Nombre de plaques :</label>

        <input  class="form-control" type="number" name="nombre_plaques" required>

        <label class="form-label" for="categorie">Catégorie :</label>
        <select class="form-select" name="categorie" required>
            <option value="avis google">Avis Google</option>
            <option value="tiktok">TikTok</option>
            <option value="instagram">Instagram</option>
            <option value="wifi">WiFi</option>
            <option value="e-mail">E-mail</option>
        </select>
       <input type="submit" class="btn btn-danger  mt-3 mt-sm-0" onclick="confirm('êtes vous sûr de commander')" name="orderPlate"> 
</form>

<?php }elseif($userController->user()){ ?>
<?php if (!empty($plateWithToken)) { ?>
    <?= $HtmlGenerate->generateILVProduct($plateWithToken, $category); ?>
<?php } ?>

<?php if (!empty($plaques)) { ?>
    <!-- Title-->
    <div class="d-sm-flex flex-wrap justify-content-between align-items-center border-bottom">
        <h2 class="h3 py-2 me-2 text-center text-sm-start">Vos produits ilv digital<span class="badge bg-faded-accent fs-m text-body align-middle ms-2"><?= $plaques['rowCount']; ?></span></h2>
    </div>
    <!-- <div class="overflow-auto"> -->
    <?php foreach ($plaques['result'] as $plaque) { ?>
         
        <?= $HtmlGenerate->generateILVProduct($plaque, $category); ?>
    <?php } ?>
    <!-- </div> -->
<?php } if($plaques['rowCount']==0) { ?>
    <p>Vous n'avez pas de ILV</p>
<?php } ?>
<?php } ?>
