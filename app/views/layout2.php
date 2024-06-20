<!DOCTYPE html>
<html lang="FR_fr">
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
    <link rel="stylesheet" media="screen" href="public/assets/css/theme.min.css">
    <style>
        body{
            background-color: #F0F4FF !important;
        }
    </style>
</head>
<body>
<div class="container py-3 py-lg-5 mt-4 mb-3">
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
<?= $content; ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>