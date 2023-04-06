<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title><?= $pagetitle ?></title>
    <link rel="stylesheet" href="/ressources/css/acceuil.css">
    <style>
        @import url('https://fonts.googleapis.com/css?family=Poppins:400,700,900');
    </style>

</head>
<body>
<header>

    <img  src="../ressources/img/logo.png" class="logo2"/>

    <nav>

        <ul>
            <li>
                <a href="controleurFrontal.php?action=afficherListe&controleur=utilisateur">Accueil</a>
            </li>
            <li>
                <a href="controleurFrontal.php?action=afficherListe&controleur=noeudCommune">Carte</a>
            </li>
            <li>
                <a href="controleurFrontal.php?action=afficherListe&controleur=utilisateur">Profil</a>
            </li>
            <li>
                <a class="connect" href="controleurFrontal.php?action=afficherListe&controleur=utilisateur">Connexion</a>
            </li>

            <?php

            use App\PlusCourtChemin\Lib\ConnexionUtilisateur;

            if (!ConnexionUtilisateur::estConnecte()) {
                echo <<<HTML
                    <li>
                        <a href="controleurFrontal.php?action=afficherFormulaireConnexion&controleur=utilisateur">
                            <img alt="login" src="../ressources/img/enter.png" width="18">
                        </a>
                    </li>
                    HTML;
            } else {
                $loginHTML = htmlspecialchars(ConnexionUtilisateur::getLoginUtilisateurConnecte());
                $loginURL = rawurlencode(ConnexionUtilisateur::getLoginUtilisateurConnecte());
                echo <<<HTML
                    <li>
                        <a href="controleurFrontal.php?action=afficherDetail&controleur=utilisateur&login=$loginURL">
                            <img alt="user" src="../ressources/img/user.png" width="18">
                            $loginHTML
                        </a>
                    </li>
                    <li>
                        <a href="controleurFrontal.php?action=deconnecter&controleur=utilisateur">
                            <img alt="logout" src="../ressources/img/logout.png" width="18">
                        </a>
                    </li>
                    HTML;
            }
            ?>
        </ul>
    </nav>

</header>
<main>
    <div class="main-container">
        <div class="alert alert-img" id="div1">
            <img src="../ressources/img/logo.png" alt="logo2"/>

            <p>Trouvez votre chemin, ou que vous soyez !</p>
        </div>


        <div class="alert alert-info" id="div2">
            <p>Waps, votre nouvel assistant géographique, vous permet de trouver le chemin le plus court entre un point A et un point B.</p>
            <p>Développé par cinq étudiants de l’IUT Montpellier / Sète, Waps saura vous aider dans tous vos trajets du quotidien.</p>
            <p>Avec ses fonctionnalités de calcul de trajets les plus courts, vous réaliserez un gain de temps considérable sur tous vos déplacements.</p>
            <button class="bouton-decouverte">Découvrez Waps !</button>
        </div>

    </div>


</main>

<footer>
    <p>
        Copyleft Romain Lebreton
    </p>
</footer>
</body>

</html>