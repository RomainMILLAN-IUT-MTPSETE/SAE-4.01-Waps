<link rel="stylesheet" href="../ressources/css/plusCourtChemin.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.7.1/leaflet.css"/>
<script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.7.1/leaflet.js"></script>
<script type="text/javascript" src="../ressources/js/autocomplete.js" defer></script>
<script type="text/javascript" src="../ressources/js/map.js" defer></script>
<div class="pcc-container">
    <div class="choose">
        <h2 class="choose_title">Choisissez votre itinéraire !</h2>
        <form action="" method="post" autocomplete="off">
            <div class="inputDiv">
                <p class="Input-label" for="nomCommuneDepart_id">Point de départ</p>
                <div class="ConteneurAutoCompletionDepart">
                    <input class="Input-field" type="text" value="" placeholder="Ex : Sète" name="nomCommuneDepart"
                           id="nomCommuneDepart_id" required>
                    <div class="autoCompletionDepart" id="autoCompletionDepart"></div>
                </div>
            </div>
            <div class="inputDiv">
                <p class="Input-label" for="nomCommuneArrivee_id">Point d’arrivée</p>
                <div class="ConteneurAutoCompletionArrivee">
                    <input class="Input-field" type="text" value="" placeholder="Ex : Montpellier"
                           name="nomCommuneArrivee" id="nomCommuneArrivee_id" required>
                    <div class="autoCompletionArrivee" id="autoCompletionArrivee"></div>
                </div>
            </div>
            <!-- <input type="hidden" name="XDEBUG_TRIGGER"> -->
            <input class="Input-submit" type="submit"/>
        </form>
    </div>
    <div class="pcc-resultat">
        <div class="map" id="map"></div>
        <div class="resultat">
            <?php if (!empty($_POST)) { ?>
                <p>
                    Le trajet entre <?= $nomCommuneDepart ?> et <?= $nomCommuneArrivee ?> fait <?= $distance ?>km. 
                    <?php if (App\PlusCourtChemin\Modele\HTTP\Cookie::existeCle("temps_calcul")) echo("<br/>Le temps de calcul est de: " . App\PlusCourtChemin\Modele\HTTP\Cookie::lire("temps_calcul") . " secondes.") ?>
                </p>
            <?php } ?>
        </div>
    </div>
</div>
