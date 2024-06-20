<script>
        $(document).ready(function() {
            // Fonction pour récupérer les suggestions d'autocomplétion
            function getAutocompleteSuggestions(input) {
                $.ajax({
                    url: "autocomplite.php", // Chemin vers le fichier PHP qui effectue la recherche d'autocomplétion
                    method: "POST",
                    data: { input: input },
                    dataType: "json",
                    success: function(response) {
                        // Afficher les suggestions dans la div "suggestions"
                        var suggestionsHtml = "";
                        response.forEach(function(prediction) {
                            suggestionsHtml += "<div>" + prediction.description + "</div>";
                        });
                        $("#suggestions").html(suggestionsHtml);
                    }
                });
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