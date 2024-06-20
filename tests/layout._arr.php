<?php
$c = new controller\UserController;
$action = isset($_GET["action"]) ? $_GET["action"] : "";
$currentUrl = $_SERVER['REQUEST_URI'];
?>
<!DOCTYPE html>
<html lang="Fr_fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?=$title;?></title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css"
    integrity="sha512-MV7K8+y+gLIBoVD59lQIYicR65iaqukzvf/nwasF0nqhPay5w/9lJmVM2hMDcnK1OnMGCdVK+iQrJ7lzPJQd1w=="
    crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="public/assets/css/style.css">
</head>
<body>

<div class="header">
<div class="logo__mobile"><!-- logo -->
<div class="spinner logo spinner--single">
    <a href="?action=home"><img src="public/assets/images/itPortefolio-logo.png" alt="it portfolie." width="150" height="150"></a>
</div>
</div><!-- logo end -->

<!-- icon menu Mobile -->
<i class="fa-solid fa-bars" id="menuIcon"></i>
<!-- menu mobile end -->
<!-- arrow menu -->
<div class="divder" id="divder"></div>
<!-- end arrow menu -->
<!-- nav -->
<nav class="nav__bar" id="nav__bar"aria-label="Principal">
    <ul class="nav__items" id="mobileMenu">
        <li class="nav__item"><a class="nav__link" href=""><span class="nav__label">à props</span></a></li>
        <li class="nav__item"><a class="nav__link" href=""><span class="nav__label">Aide</span></a></li>
        <li class="nav__item"><a class="nav__link" href=""><span class="nav__label">Contact</span></a></li>
    </ul>
</nav>
<!-- end nav page home -->
</div>

<div class="container">

<?php if (!empty($alert)) {?>
<div id="alert" class="alert">
<i class="fa-solid fa-xmark closeButton" id="close"></i>
            <?=$alert;?>
</div>
 <?php }elseif(isset($alertValid) && !empty($alertValid)){?>
 <div id="alert" class="alert valid" >
 <i class="fa-solid fa-xmark closeButton" id="close"></i>
        <?=$alertValid;?>
</div>
 <?php } ?>   

<!-- content -->
<?=$content;?>
<!-- content -->

</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
    var arrow = document.getElementById('divder');
    var menuIcon = document.getElementById('menuIcon');
    var mobileMenu = document.getElementById('mobileMenu');
  
    if(menuIcon !== null){
    menuIcon.addEventListener('click', function () {
        if (mobileMenu.style.display === 'block') {
            mobileMenu.style.display = 'none';
            arrow.style.display ='none';
        } else {
            mobileMenu.style.display = 'block';
            arrow.style.display ='block';
        }
    });
}
    
});
    
    
    var  alert = document.getElementById('alert'); 
    var  close = document.getElementById('close');
    if(close !== null){
    close.addEventListener('click', function () {      
        console.log(alert);
        alert.classList.add('displayNone');
    });
    }
</script>

</body>
</html>




