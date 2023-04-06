<link rel="stylesheet" href="../ressources/css/formUser.css">
<div class="form-user">
    <form method="<?= $method ?>" action="controleurFrontal.php">
        <h2 class="form-user-title">Mon formulaire :</h2>
        <p class="InputUser-div">
            <label class="InputUser-item" for="login_id">Login&#42;</label>
            <input class="InputUser-field" type="text" value="" placeholder="dev@waps.fr" name="login" id="login_id" required>
        </p>
        <p class="InputUser-div">
            <label class="InputUser-item" for="email_id">Email&#42;</label>
            <input class="InputUser-field" type="email" value="" placeholder="rlebreton@yopmail.com" name="email" id="email_id" required>
        </p>
        <p class="InputUser-div">
            <label class="InputUser-item" for="prenom_id">Prenom&#42;</label>
            <input class="InputUser-field" type="text" value="" placeholder="Romain" name="prenom" id="prenom_id" required>
        </p>
        <p class="InputUser-div">
            <label class="InputUser-item" for="nom_id">Nom&#42;</label>
            <input class="InputUser-field" type="text" value="" placeholder="Lebreton" name="nom" id="nom_id" required>
        </p>
        <p class="InputUser-div">
            <label class="InputUser-item" for="mdp_id">Mot de passe&#42;</label>
            <input class="InputUser-field" type="password" value="" placeholder="************" name="mdp" id="mdp_id" required>
        </p>
        <p class="InputUser-div">
            <label class="InputUser-item" for="mdp2_id">Vérification du mot de passe&#42;</label>
            <input class="InputUser-field" type="password" value="" placeholder="************" name="mdp2" id="mdp2_id" required>
        </p>
        <?php

        use App\PlusCourtChemin\Lib\ConnexionUtilisateur;

        if (ConnexionUtilisateur::estAdministrateur()) {
            ?>
            <p class="InputUser-div">
                <label class="InputUser-item" for="estAdmin_id">Administrateur</label>
                <input class="InputUser-field" type="checkbox" placeholder="" name="estAdmin" id="estAdmin_id">
            </p>
        <?php } ?>

        <input type='hidden' name='action' value='creerDepuisFormulaire'>
        <input type='hidden' name='controleur' value='utilisateur'>

        <input class="InputUser-submit" type="submit" value="Crée son compte" />
    </form>
</div>