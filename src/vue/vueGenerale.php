<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Waps - <?= $pagetitle ?></title>
    <link rel="stylesheet" href="../ressources/css/main.css">
    <link rel="stylesheet" href="../ressources/css/alert.css">
    <link rel="stylesheet" href="../ressources/css/footer.css">
    <link rel="stylesheet" href="https://unicons.iconscout.com/release/v3.0.0/css/line.css">
    <link rel="shortcut icon" href="../ressources/img/icone.svg" type="image/x-icon">
</head>
<body>
    <header class="header">
        <a href="controleurFrontal.php" class="nav__logo">
            <img class="logo" src="../ressources/img/logo.png" alt="logo"/>
        </a>
        <nav class="nav__container">
            <form action="controleurFrontal.php" method="get">
                <input type="hidden" name="action" value="rechercher">
                <input type="hidden" name="controleur" value="noeudCommune">
                <input type="text" name="search" id="search" placeholder="Rechercher"/>
            </form>
            <a class="nav_a" href="controleurFrontal.php?action=plusCourtChemin&controleur=noeudCommune">Accueil</a>
            <a class="nav_a" href="controleurFrontal.php?action=afficherListe&controleur=noeudCommune">Communes</a>
            <!--<a class="nav_a" href="controleurFrontal.php?action=afficherListe&controleur=utilisateur">Utilisateurs</a>-->

            <?php

            use App\PlusCourtChemin\Lib\ConnexionUtilisateur;

            if (!ConnexionUtilisateur::estConnecte()) {
                echo <<<HTML
                    <a href="controleurFrontal.php?action=afficherFormulaireConnexion&controleur=utilisateur"><button class="nav_button">Connexion</button></a>
                    HTML;
            } else {
                $loginHTML = htmlspecialchars(ConnexionUtilisateur::getLoginUtilisateurConnecte());
                $loginURL = rawurlencode(ConnexionUtilisateur::getLoginUtilisateurConnecte());
                echo <<<HTML
                    <a href="controleurFrontal.php?action=afficherDetail&controleur=utilisateur&login=$loginURL"><button class="nav_button">Mon Profil</button></a>
                HTML;
            }
            ?>
        </nav>
    </header>
    <div>
        <?php
        foreach (["success", "info", "warning", "danger"] as $type) {
            foreach ($messagesFlash[$type] as $messageFlash) {
                echo <<<HTML
                    <div class="alert alert-$type">
                        $messageFlash
                    </div>
                    HTML;
            }
        }
        ?>
    </div>
    <main>
        <?php
        /**
         * @var string $cheminVueBody
         */
        require __DIR__ . "/{$cheminVueBody}";
        ?>
    </main>
    <footer>
        <p>Waps 2023 © MILLAN, TREGUIER, CHARRADE, PIERRE, CALAS</p>
    </footer>
</body>

</html>