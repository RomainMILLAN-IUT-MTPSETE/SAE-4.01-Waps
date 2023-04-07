<link rel="stylesheet" href="../ressources/css/listeCommunes.css">

<div class="listecom-container">
    <h2>Liste des communes</h2>

    <div class="listcom-content">
        <?php
        foreach ($noeudsCommunes as $noeudCommune) {
            echo '<a href="controleurFrontal.php?action=afficherDetail&controleur=noeudCommune&gid='.$noeudCommune->getGid().'"><span class="cnt"><img src="../ressources/img/icone.svg" alt="Icone Waps"><p>'.$noeudCommune->getNomCommune().'</p></span></a>';
        }
        ?>
    </div>
</div>
