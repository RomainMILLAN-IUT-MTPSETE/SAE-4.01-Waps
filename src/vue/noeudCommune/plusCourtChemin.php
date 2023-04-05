<link rel="text/javascript">
<form action="" method="post" autocomplete="off">
    <fieldset>
        <legend>Plus court chemin </legend>
        <p class="InputAddOn">
            <label class="InputAddOn-item" for="nomCommuneDepart_id">Nom de la commune de départ</label>
            <input class="InputAddOn-field" type="text" value="" placeholder="Ex : Menton" name="nomCommuneDepart" id="nomCommuneDepart_id" required>
        </p>
        <div id="autoCompletion">

        </div>
        <p class="InputAddOn">
            <label class="InputAddOn-item" for="nomCommuneArrivee_id">Nom de la commune de départ</label>
            <input class="InputAddOn-field" type="text" value="" placeholder="Ex : Menton" name="nomCommuneArrivee" id="nomCommuneArrivee_id" required>
        </p>
        <input type="hidden" name="XDEBUG_TRIGGER">
        <p>
            <input class="InputAddOn-field" type="submit" value="Calculer" />
        </p>
    </fieldset>
</form>

<?php if (!empty($_POST)) { ?>
    <p>
        Le plus court chemin entre <?= $nomCommuneDepart ?> et <?= $nomCommuneArrivee ?> mesure <?= $distance ?>km.
    </p>
<?php } ?>
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