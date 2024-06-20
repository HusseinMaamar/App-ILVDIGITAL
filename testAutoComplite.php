<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Générer le lien vers les avis Google</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
</head>
<body>
    <h1>Générer le lien vers les avis Google</h1>
    <form id="searchForm">
        <label for="nomEtablissement">Nom de l'établissement :</label>
        <input type="text" id="nomEtablissement" name="nomEtablissement" required>
        <div id="suggestions"></div>
    </form>

    <script>
        $(document).ready(function() {
            // Fonction pour récupérer les suggestions d'autocomplétion en fonction du texte saisi
            function getAutocompleteSuggestions(input) {
                $.ajax({
                    url: "autocomplite.php", // Chemin vers le fichier PHP qui effectue la recherche d'autocomplétion
                    method: "POST",
                    data: { input: input },
                    dataType: "json",
                   success: function(response) {
                        // Ajouter les suggestions à la liste déroulante
                        var select = $("#nomEtablissement");
                        select.empty(); // Effacer les anciennes options
                        select.append($("<option>").attr("value", "").text("Choisir un établissement")); // Ajouter une option vide par défaut
                        response.forEach(function(prediction) {
                            select.append($("<option>").attr("value", prediction.description).text(prediction.description));
                        });
                    }
                });
            }
            }

            // Détecter les changements dans le champ de saisie et récupérer les suggestions d'autocomplétion
            $("#nomEtablissement").on("input", function() {
                var input = $(this).val();
                if (input.length > 0) {
                    getAutocompleteSuggestions(input);
                } else {
                    $("#suggestions").html(""); // Effacer les suggestions si le champ est vide
                }
            });
        });
    </script>
</body>
</html>
