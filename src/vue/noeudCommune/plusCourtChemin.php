<link rel="stylesheet" href="../ressources/css/plusCourtChemin.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.7.1/leaflet.css"/>
<script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.7.1/leaflet.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet-routing-machine/3.2.12/leaflet-routing-machine.css" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet-routing-machine/3.2.12/leaflet-routing-machine.min.js"></script>
<script type="text/javascript" src="../ressources/js/autocomplete.js" defer></script>
<script type="text/javascript" src="../ressources/js/map.js" defer></script>
<div class="pcc-container">
    <div class="choose">
        <h2 class="choose_title">Choisissez votre itinéraire !</h2>
        <form action="" autocomplete="off">
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
            <button id="button" class="Input-submit" type="button">Calculer</button>
        </form>
    </div>
    <div class="pcc-resultat">
        <div class="map" id="map"></div>
        <div class="resultat" id="resultat"></div>
    </div>
</div>
