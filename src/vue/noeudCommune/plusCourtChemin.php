<link rel="text/javascript">
<link rel="stylesheet" href="../ressources/css/plusCourtChemin.css">

<div class="choose">
    <h2 class="choose_title">Choisissez votre itinéraire !</h2>

    <form action="" method="post" autocomplete="off">
        <div class="inputDiv">
            <p class="Input-label" for="nomCommuneDepart_id">Point de départ</p>
            <input class="Input-field" type="text" value="" placeholder="Ex : Sète" name="nomCommuneDepart" id="nomCommuneDepart_id" required>
        </div>
        <div class="autoCompletionDepart" id="autoCompletionDepart"></div>
        <div class="inputDiv">
            <p class="Input-label" for="nomCommuneArrivee_id">Point d’arrivé</p>
            <input class="Input-field" type="text" value="" placeholder="Ex : Montpellier" name="nomCommuneArrivee" id="nomCommuneArrivee_id" required>
        </div>
        <div class="autoCompletionArrivee" id="autoCompletionArrivee"></div>
        <input class="Input-submit" type="submit" value="Calculer" />
    </form>
</div>
<div class="resultat">
    <?php if (!empty($_POST)) { ?>
        <p>
            <?= $nomCommuneDepart ?> -> <?= $nomCommuneArrivee ?> = <?= $distance ?>km. <?php if(App\PlusCourtChemin\Modele\HTTP\Cookie::existeCle("temps_calcul")) echo("Temps calcul = " . App\PlusCourtChemin\Modele\HTTP\Cookie::lire("temps_calcul") . " secondes.") ?>
        </p>
    <?php } ?>
</div>
