<section class="row g-0">
          <!-- <div class="col-md-6 bg-position-center bg-size-cover bg-secondary order-md-2" style="min-height: 15rem; background-image: url(img/about/05.jpg);"></div> -->
          <div class="col-md-6 px-3 px-md-5 py-3 order-md-1">
            <div class="mx-auto py-lg-5" style="max-width: 35rem;">
              <h2 class="h3 mb-2">Envoyez rapidement vos documents</h2>
              <p class="fs-sm text-muted pb-2">Utilisez le formulaire ci-dessous pour envoyer vos documents en tout sécurité</p>
              <form class="needs-validation row g-4" action="" method="post" novalidate  enctype="multipart/form-data">
                <div class="col-sm-6">
                  <label class="fs-sm text-muted pb-2" for="ci-name">Nom et prénom *</label>
                  <input class="form-control" type="text" id="ci-name"  placeholder="Votre nom et prénom" name="subject" required>
                </div>
                <div class="col-12">
                    <label class="fs-sm text-muted pb-2" for="ci-message"> Message (facultatif)</label>
                  <textarea class="form-control" rows="4" id="ci-message" name="message" placeholder="Message"></textarea>
                </div>
                <div class="col-12">    
                    <label class="fs-sm text-muted pb-2" for="ci-message">Pièce jointe * <i class="ci-clip fs-4"></i> </label>
                    <input class="form-control" type="file" name="attachment" required>
                </div>
                <div class="col-12">
                  <button class="btn btn-info btn-shadow" type="submit">Envoyer</button>
                </div>
              </form>
            </div>
          </div>
</section>
<h6 class="mt-3">Courriel:</h6>
<span class="fs-sm mt-0 font-weight-bold text-muted" onclick="copyEmail()" id="email" style="cursor:pointer;"><strong><u><?=$resultatCheckPlaque[0];?></strong></span><span class="fs-sm text-info" onclick="copyEmail()">  <i class="ci-mail me-1 fs-sm"></i> Copier l'e-mail</u></span>


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
