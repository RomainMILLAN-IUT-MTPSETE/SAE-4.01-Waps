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
    <script type="text/javascript" src="../ressources/js/menu.js" defer></script>
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
        <div class="nav__mobile_open" id="nav__mobile_open">
            <nav>
                <div class="nav__mobile_row">
                    <a href="controleurFrontal.php?action=afficherListe&controleur=noeudCommune"><img src="../ressources/img/home.svg" alt="Icone home"><p>Communes</p></a>
                    <a href="controleurFrontal.php?action=plusCourtChemin&controleur=noeudCommune"><img src="../ressources/img/carte.svg" alt="Icone carte"><p>Carte</p></a>
                    <?php
                    if(ConnexionUtilisateur::estConnecte()){
                        $loginHTML = htmlspecialchars(ConnexionUtilisateur::getLoginUtilisateurConnecte());
                        $loginURL = rawurlencode(ConnexionUtilisateur::getLoginUtilisateurConnecte());
                        echo <<<HTML
                        <a href="controleurFrontal.php?action=afficherDetail&controleur=utilisateur&login=$loginURL"><img src="../ressources/img/profil.svg" alt="Icone profil"><p>Profil</p></a>
                        HTML;
                    }
                    ?>
                </div>
                <?php
                if (!ConnexionUtilisateur::estConnecte()) {
                    echo <<<HTML
                    <a class="nav__mobile_connect" href="controleurFrontal.php?action=afficherFormulaireConnexion&controleur=utilisateur"><button class="nav_button">Connexion</button></a>
                    HTML;
                }
                ?>
            </nav>
            <a href="#" class="nav__mobile_close" id="nav__mobile_close_menu"><img src="../ressources/img/mobilemenuclose.svg" alt="Icone menu close"></a>
        </div>
        <div class="nav__mobile">
            <a href="controleurFrontal.php?action=plusCourtChemin&controleur=noeudCommune"><img src="../ressources/img/icone.svg" alt="Icone Waps"></a>
            <a href="#"><img onclick="" id="nav__mobile_open_menu" src="../ressources/img/mobilemenu.svg" alt="Icone Waps"></a>
        </div>
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