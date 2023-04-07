<link rel="stylesheet" href="../ressources/css/detailCommune.css">

<div class="detailCommune-container">
    <a href="controleurFrontal.php?action=afficherListe&controleur=noeudCommune"><img src="../ressources/img/icone.svg" alt="Icone Waps"><p>Retourner à la liste des communes</p></a>
    <h2><?= $noeudCommune->getNomCommune() ?></h2>

    <div class="detailCommune-content">
        <p>Identifiant Route500: <?= $noeudCommune->getId_rte500() ?></p>
        <p>Nom chef: <?= $noeudCommune->getNomChf() ?></p>
        <p>Statut: <?= $noeudCommune->getStatus() ?></p>
        <p>Superficie: <?= $noeudCommune->getSuperfecie() ?></p>
        <p>Population: <?= $noeudCommune->getPopulation() ?></p>
        <p>Position: <?= $noeudCommune->getGeom() ?></p>
    </div>
</div>