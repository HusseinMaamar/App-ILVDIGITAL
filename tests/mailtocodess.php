<a href="mailto:<?=$resultatCheckPlaque[0];?>"> Envoyer un e-mail </a>

<p id="email"><?=$resultatCheckPlaque[0];?></p>

<button onclick="copyEmail()">Copier l'e-mail</button>


<script>
        function copyEmail() {
            // Sélectionner l'élément contenant l'adresse e-mail
            var emailElement = document.getElementById("email");

            // Créer une zone de texte temporaire
            var tempInput = document.createElement("textarea");

            // Copier le contenu de l'élément dans la zone de texte temporaire
            tempInput.value = emailElement.textContent;

            // Ajouter la zone de texte temporaire à la page
            document.body.appendChild(tempInput);

            // Sélectionner tout le texte dans la zone de texte
            tempInput.select();
            tempInput.setSelectionRange(0, 99999); /* Pour les appareils mobiles */

            // Copier le texte dans le presse-papiers
            document.execCommand("copy");

            // Supprimer la zone de texte temporaire de la page
            document.body.removeChild(tempInput);

            // Afficher une confirmation (facultatif)
            alert("Adresse e-mail copiée : " + emailElement.textContent);
        }
    </script>
