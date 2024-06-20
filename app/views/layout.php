<?php
$c = new controller\UserController;
$action = isset($_GET["action"]) ? $_GET["action"] : "";
$currentUrl = $_SERVER['REQUEST_URI'];
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <base href="/app.ilvdigital/">
    <title><?= $title; ?></title>
    <!-- SEO Meta Tags-->
    <meta name="description" content="Application pour activer des ilv digital">
    <meta name="keywords" content="avis google">
    <meta name="author" content="Hussein Maamar">
    <!-- Viewport-->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Favicon and Touch Icons-->
    <link rel="apple-touch-icon" sizes="180x180" href="apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="favicon-16x16.png">
    <link rel="manifest" href="site.webmanifest">
    <link rel="mask-icon" color="#fe6a6a" href="safari-pinned-tab.svg">
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="theme-color" content="#ffffff">
    <!-- Main Theme Styles + Bootstrap-->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <link rel="stylesheet" media="screen" href="public/assets/css/theme.min.css">
    <style>
        body{
            background-color: #F0F4FF !important;
        }
        .item-autocomplite{
            cursor: pointer;
        }
    </style>
</head>

<body class="handheld-toolbar-enabled">
    <!-- main -->
    <main class="page-wrapper">

    
        <!-- Dashboard header-->
        <?php if ($c->connect()): ?>
        <div class="page-title-overlap bg-accent pt-4">
            <div class="container d-flex flex-wrap flex-sm-nowrap justify-content-center justify-content-sm-between align-items-center pt-2">
                <div class="d-flex align-items-center pb-2 me-2">
                    <div class="position-relative flex-shrink-0" style="width: 10.375rem;">
                        <img class="" src="public/assets/images/logobleu.png" alt="Createx Studio">
                    </div>
                </div>
            </div>
        </div><!-- end header -->

        <div class="container mb-5 pb-3">
            <div class="bg-light shadow-lg rounded-3 overflow-hidden">
                <div class="row">
                    <!-- Sidebar-->
                    
                    <aside class="col-lg-4 pe-xl-5">
                        <!-- Account menu toggler (hidden on screens larger 992px)-->
                        <div class="d-block d-lg-none p-4"><a class="btn btn-outline-accent d-block" href="#account-menu"
                                data-bs-toggle="collapse"><i class="ci-menu me-2"></i>Menu</a></div>
                        <!-- Actual menu-->
                        <div class="h-100 border-end mb-2">
                            <div class="d-lg-block collapse" id="account-menu">
                                <div class="bg-secondary p-4">
                                    <h3 class="fs-sm mb-0 text-muted">Menu</h3>
                                </div>
                               <ul class="list-unstyled mb-0">
                                <li class="border-bottom mb-0">
                                   <a class="nav-link-style d-flex align-items-center px-4 py-3" href="account" >
                                    <div class="d-flex align-items-center justify-items-center ">
                                       <div class="media-tab-media shadow-none bg-success text-center p-3"><span class="fs-lg fw-semibold text-light"><?= strtoupper(substr($_SESSION['user']['userName'],0,1)) ;?></span></div>
                                     <div class="ps-3">
                                 <h6 class="media-tab-title text-dark text-nowrap mb-0"><?= ucwords($_SESSION['user']['userName']);?> </h6>
                                 </div>
                               </div>
                              </a>
                              </li>
                           </ul>
                                <ul class="list-unstyled mb-0">
                                    <li class="border-bottom mb-0">
                                        <a class="nav-link-style d-flex align-items-center px-4 py-3 <?= $action == 'dash' ? 'active' : ($action == '' ? 'active' : '') ?>
                                       " href="dashboard"><i
                                                class="ci-package opacity-60 me-2"></i>Tableau de bord</a>
                                    </li>
                                </ul>
                                <ul class="list-unstyled mb-0">
                                    <?php if($c->user()):?>
                                    <li class="border-bottom mb-0"><a class="nav-link-style d-flex align-items-center px-4 py-3"
                                            href="https://ilvdigital.fr/aide/" target="_blank"><i class="ci-settings opacity-60 me-2"></i>Aide</a>
                                    </li>
                                    <?php endif; ?>
                                    <?php if($c->admin()):?>
                                    <li class="border-bottom mb-0"><a class="nav-link-style d-flex align-items-center px-4 py-3"
                                            href="download"><i class="ci-folder opacity-60 me-2"></i>Dossiers de commandes</a>
                                    </li>
                                    <?php endif; ?>
                                    <li class="mb-0"><a class="nav-link-style d-flex align-items-center px-4 py-3" href="logout"><i
                                                class="ci-sign-out opacity-60 me-2"></i>Déconnexion</a>
                                    </li>
                                </ul>
                                <hr>
                            </div>
                        </div>
                    </aside><!-- Sidebar-->
                    
                    <!-- Content-->
                    <section class="col-lg-8 pt-lg-4 pb-4 mb-3">
                        <div class="pt-2 px-4 ps-lg-0 pe-xl-5" >
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
                            <!-- content -->
                            <?= $content; ?>
                            <!-- content -->
                        </div>
                    </section>
                </div>
            </div>
        </div>
        <?php else: ?>
            <?= $content; ?>
        <?php endif;?>
    </main>

    <!-- Back To Top Button-->
    <!-- <a class="btn-scroll-top bg-accent" href="#top" data-scroll><span class="btn-scroll-top-tooltip text-accent fs-sm me-2">Top</span><i class="btn-scroll-top-icon ci-arrow-up"></i>
    </a> -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="public/assets/vendor/smooth-scroll/dist/smooth-scroll.polyfills.min.js"></script>
    <script src="public/assets/js/theme.min.js"></script>
    <script src="public/assets/js/script.js"></script> 
   
</body>
</html>
