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

    <script>
        $(document).ready(function() {   
            // Fonction pour récupérer les suggestions d'autocomplétion en fonction du texte saisi
            function getAutocompleteSuggestions(input, suggestionsList ) {
                $.ajax({
                    url: "autocomplite.php", // Chemin vers le fichier PHP qui effectue la recherche d'autocomplétion
                    method: "POST",
                    data: { input: input },
                    dataType: "json",
                    success: function(response) {
                        // Afficher les suggestions dans la liste
                        suggestionsList.empty(); // Vider la liste des anciennes suggestions
                        response.forEach(function(prediction) {
                            var listItem = $("<li class='list-group-item list-group-item-action item-autocomplite' >").text(prediction.description);
                            suggestionsList.append(listItem);
                        });
                    }
                });
            }
            // Détecter les changements dans le champ de saisie et récupérer les suggestions d'autocomplétion
            $("#nomEtablissement").on("input", function() {
                var input = $(this).val();
                var suggestionsList = $(this).next("#suggestions");
                if (input.length > 0) {
                    getAutocompleteSuggestions(input, suggestionsList);
                } else {
                    $("#suggestions").html(""); // Effacer les suggestions si le champ est vide
                }
            });

            $(document).on("click", "#suggestions li", function() {
                var suggestionValue = $(this).text(); // Récupérer la valeur de la suggestion
                $("#nomEtablissement").val(suggestionValue); // Assigner la valeur de la suggestion à l'input
                $("#suggestions").html(""); // Effacer les suggestions après avoir sélectionné une
            });

        });
    </script>
<?php } ?>

<?php if (!empty($plaques)) { ?>
    <!-- Title-->
    <div class="d-sm-flex flex-wrap justify-content-between align-items-center border-bottom">
        <h2 class="h3 py-2 me-2 text-center text-sm-start">Vos produits ilv digital<span class="badge bg-faded-accent fs-m text-body align-middle ms-2"><?= $plaques['rowCount']; ?></span></h2>
    </div>
    <!-- <div class="overflow-auto"> -->
    <?php foreach ($plaques['result'] as $plaque) { ?>
         
        <?= $HtmlGenerate->generateILVProduct($plaque, $category); ?>

        <script>
        $(document).ready(function() {

            
            // Fonction pour récupérer les suggestions d'autocomplétion en fonction du texte saisi
            function getAutocompleteSuggestions(input, suggestionsList ) {
                $.ajax({
                    url: "autocomplite.php", // Chemin vers le fichier PHP qui effectue la recherche d'autocomplétion
                    method: "POST",
                    data: { input: input },
                    dataType: "json",
                    success: function(response) {
                        // Afficher les suggestions dans la liste
                        suggestionsList.empty(); // Vider la liste des anciennes suggestions
                        response.forEach(function(prediction) {
                            var listItem = $("<li class='list-group-item list-group-item-action item-autocomplite' >").text(prediction.description);
                            suggestionsList.append(listItem);
                        });
                    }
                });
            }

            // Détecter les changements dans le champ de saisie et récupérer les suggestions d'autocomplétion

            $("#<?php echo $plaque['id'] .'nomEtablissement' ; ?>").on("input", function() {
                var input = $(this).val();
                var suggestionsList = $(this).next("#<?php echo $plaque['id'].'suggestions'; ?>");
                var test = $("#<?php echo $plaque['id'] .'nomEtablissement' ; ?>");
                console.log(test);
                if (input.length > 0) {
                    getAutocompleteSuggestions(input, suggestionsList);
                } else {
                    $("#suggestions").html(""); // Effacer les suggestions si le champ est vide
                }
            });

            $(document).on("click", "#<?php echo $plaque['id'].'suggestions'; ?> li", function() {
                var test = $("#<?php echo $plaque['id'].'suggestions'; ?> li");
                console.log(test);
                var suggestionValue = $(this).text(); // Récupérer la valeur de la suggestion
                $("#<?php echo $plaque['id'] .'nomEtablissement' ; ?>").val(suggestionValue); // Assigner la valeur de la suggestion à l'input
                $("#<?php echo $plaque['id'].'suggestions'; ?>").html(""); // Effacer les suggestions après avoir sélectionné une
            });

        });
    </script>

    <?php } ?>
    <!-- </div> -->
<?php } if($plaques['rowCount']==0) { ?>
    <p>Vous n'avez pas de ILV</p>
<?php } ?>
<?php } ?>

