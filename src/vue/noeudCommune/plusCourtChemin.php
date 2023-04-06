<link rel="text/javascript">
<link rel="stylesheet" href="../ressources/css/plusCourtChemin.css">

<div class="choose">
    <h2 class="choose_title">Choisissez votre itinéraire !</h2>

    <form action="" method="post" autocomplete="off">
        <div class="inputDiv">
            <p class="Input-label" for="nomCommuneDepart_id">Point de départ</p>
            <input class="Input-field" type="text" value="" placeholder="Ex : Sète" name="nomCommuneDepart" id="nomCommuneDepart_id" required>
        </div>
        <div class="autoCompletion" id="autoCompletion">

        </div>
        <div class="inputDiv">
            <p class="Input-label" for="nomCommuneArrivee_id">Point d’arrivé</p>
            <input class="Input-field" type="text" value="" placeholder="Ex : Montpellier" name="nomCommuneArrivee" id="nomCommuneArrivee_id" required>
        </div>
        <input type="hidden" name="XDEBUG_TRIGGER">

        <input class="Input-submit" type="submit" value="Calculer" />
    </form>
</div>
<div class="resultat">
    <?php if (!empty($_POST)) { ?>
        <p>
            <?= $nomCommuneDepart ?> -> <?= $nomCommuneArrivee ?> = <?= $distance ?>km.
        </p>
    <?php } ?>
</div>

<script type="text/javascript"> 
    <?php 
    use App\PlusCourtChemin\Modele\Repository\NoeudCommuneRepository;
    $communes = (new NoeudCommuneRepository)->getNomsCommunes();
    ?>
    let communes = JSON.parse(atob('<?php echo base64_encode(json_encode($communes));?>'));
    document.getElementById('nomCommuneDepart_id').addEventListener('input', (event) => {
        while (document.getElementById('autoCompletion').hasChildNodes()) document.getElementById('autoCompletion').removeChild(document.getElementById('autoCompletion').firstChild);
        const valeur = event.target.value;
        const communesSelectionnees = communes.filter((commune) => {
            return commune.toLowerCase().startsWith(valeur.toLowerCase());
        }).slice(0, 5);
        for (let i = 0; i < communesSelectionnees.length; i++) {
            let temp = document.createElement(`p`);
            temp.setAttribute(`id`, `champ-ville-${i}`);
            temp.setAttribute(`class`, ``);
            temp.innerHTML = communesSelectionnees[i];
            document.getElementById('autoCompletion').appendChild(temp);
	    }
    })
</script>