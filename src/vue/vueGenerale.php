<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Waps - <?= $pagetitle ?></title>
    <link rel="stylesheet" href="../ressources/css/main.css">
    <link rel="stylesheet" href="../ressources/css/bootstrap.css">
    <link rel="stylesheet" href="https://unicons.iconscout.com/release/v3.0.0/css/line.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.7.1/leaflet.css" />
    <link rel="shortcut icon" href="../ressources/img/icone.svg" type="image/x-icon">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.7.1/leaflet.js"></script>
</head>
<body>
    <header class="header">
        <a href="controleurFrontal.php" class="nav__logo">
            <img class="logo" src="../ressources/img/logo.png" alt="logo"/>
        </a>
        <nav class="nav__container">
            <a class="nav_a" href="controleurFrontal.php?action=plusCourtChemin&controleur=noeudCommune">Accueil</a>
            <a class="nav_a" href="controleurFrontal.php?action=afficherListe&controleur=noeudCommune">Communes</a>
            <a class="nav_a" href="controleurFrontal.php?action=afficherListe&controleur=utilisateur">Utilisateurs</a>

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
                    <a href="controleurFrontal.php?action=deconnecter&controleur=utilisateur"><button class="nav_button">Deconnexion</button></a>
                    <a href="controleurFrontal.php?action=afficherDetail&controleur=utilisateur&login=$loginURL">
                            <img alt="user" src="../ressources/img/user.png" width="18">
                            $loginHTML
                        </a>
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
        <p>
            Copyleft Romain Lebreton
        </p>
    </footer>
</body>

</html>