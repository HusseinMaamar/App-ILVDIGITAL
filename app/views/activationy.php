<?php
$c = new controller\UserController;
$email = isset($_POST['email']) ? $_POST['email'] : '';
$mdp = isset($_POST['mdp']) ? $_POST['mdp'] : '';
$activation_token = isset($_GET["token"]) ? $_GET["token"] : "";
$_SESSION['getToken'] = $activation_token;
$_SESSION['csrf_token'] = bin2hex(random_bytes(32));
?>
    
<?php  if(!empty($resultatCheckPlaque) && $resultatCheckPlaque[0] !== "https://app.ilvdigital.fr/?action=activation&token=$activation_token" && $resultatCheckPlaque[1] == 1) { ?>

<?php 
header("Location: $resultatCheckPlaque[0]"); 
exit();
?>

<?php }elseif($resultatCheckPlaque[1] == 0){?>


    <?php if ($c->connect()): ?>
      
        <?php 
        header("Location: dashboard"); 
        ?>
     
    <?php else:?>
   
<div class="modal fade show p-2" id="signin-modal" style="display: block;"; tabindex="-1" role="dialog">
  <div class="modal-dialog modal-dialog-centered" role="document">
  <div class="modal-content">
   
  <div class="modal-header">
            <ul class="nav nav-tabs card-header-tabs" role="tablist">
              <li class="nav-item"><a class="nav-link text-accent  fw-medium active" id="signup-tab-link" href="#signup-tab" data-bs-toggle="tab" role="tab" aria-selected="true"><i class="ci-user me-2 mt-n1"></i>Inscription</a></li>
              <li class="nav-item"><a class="nav-link text-accent fw-medium" id="signin-tab-link" href="#signin-tab" data-bs-toggle="tab" role="tab" aria-selected="false"><i class="ci-unlocked me-2 mt-n1"></i>Connexion</a></li>
            </ul>
    </div>



    <div class="text-center mt-4">
        <img class="img-fluid mb-3" style="width: 35% !important;" src="public/assets/images/logo.png" >
        <div class="text-dark text-center  mb-2 ps-4 pe-4"> 
        <p class="fs-md">Si vous n'avez pas de compte, vous devrez créer un compte pour activer et gérer vos plaques</p>
        </div>
        <?php if(isset($message)&& !empty($message)){?> 
        <div class="text-danger">
           <p class='fs-ms'> <?= $message; ?> </p>
        </div>
       <?php } ?>
    </div>


    <div class="modal-body tab-content py-4">
         <?php include('signup.php'); ?>

         <?php include('login.php'); ?>
    </div>
    
    </div>
 
    </div>
    </div>
    <?php endif;?>

    <?php }?>


