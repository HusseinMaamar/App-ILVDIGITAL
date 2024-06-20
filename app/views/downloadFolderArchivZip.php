<form action="" method="post">
        <label for="dossier">Dossier :</label>
        <select name="dossier" id="dossier">
            <?php
          
            // Affiche chaque dossier comme une option dans la liste déroulante
            foreach ($dossiers as $dossier) {
                echo "<option value=\"$dossier\">$dossier</option>";
            }
            ?>
        </select>
    <input type="submit" value="Télécharger">
</form>