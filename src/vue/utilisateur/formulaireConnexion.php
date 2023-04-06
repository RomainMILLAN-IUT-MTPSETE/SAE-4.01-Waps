<link rel="stylesheet" href="../ressources/css/formUser.css">
<div class="form-user">
    <form method="<?= $method ?>" action="controleurFrontal.php">
        <h2 class="form-user-title">Connexion</h2>
        <p class="InputUser-div">
            <label class="InputUser-item" for="login_id">Adresse mail</label>
            <input class="InputUser-field" type="text" placeholder="dev@waps.fr" name="login" id="login_id" required>
        </p>
        <p class="InputUser-div">
            <label class="InputUser-item" for="mdp_id">Mot de passe</label>
            <input class="InputUser-field" type="password" placeholder="*************" name="mdp" id="mdp_id" required>
        </p>
        <a class="form-user-a-link" href="controleurFrontal.php?action=afficherFormulaireCreation&controleur=utilisateur">S'inscrire</a>
        <input type='hidden' name='action' value='connecter'>
        <input type='hidden' name='controleur' value='utilisateur'>
        <p>
            <input class="InputUser-submit" type="submit" value="Se connecter"/>
        </p>
    </form>
</div>