<link rel="stylesheet" href="../ressources/css/detailCommune.css">
<script type="text/javascript" src="../ressources/js/weather.js" defer></script>

<a class="returnDetailCommune" href="controleurFrontal.php?action=afficherListe&controleur=noeudCommune"><img src="../ressources/img/icone.svg" alt="Icone Waps"><p>Retourner à la liste des communes</p></a>

<div class="detailCommune-container" id="detailCommune-container">
    <h2 id="nomVille"><?= $noeudCommune->getNomCommune() ?></h2>

    <div class="detailCommune-content" id="detailCommune-content">
        <p>Nom chef: <?= $noeudCommune->getNomChf() ?></p>
        <p>Statut: <?= $noeudCommune->getStatus() ?></p>
        <p>Superficie: <?= $noeudCommune->getSuperfecie() ?> m2</p>
        <p>Population: <?= $noeudCommune->getPopulation() ?> habitants</p>
    </div>
</div>