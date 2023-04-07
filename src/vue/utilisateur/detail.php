<?php
/** @var \App\PlusCourtChemin\Modele\DataObject\Utilisateur $utilisateur */

use App\PlusCourtChemin\Lib\ConnexionUtilisateur;

$login = $utilisateur->getLogin();
$loginHTML = htmlspecialchars($login);
$prenomHTML = htmlspecialchars($utilisateur->getPrenom());
$nomHTML = htmlspecialchars($utilisateur->getNom());
$adresseMail = htmlspecialchars($utilisateur->getEmail());
$loginURL = rawurlencode($login);
?>

<link rel="stylesheet" href="../ressources/css/users.css">
<div class="user-container">
    <div class="info-user">
        <h2>Informations de l’utilisateur <a href="controleurFrontal.php?action=deconnecter&controleur=utilisateur&login=<?= $loginURL ?>"><img src="../ressources/img/logout.png" alt="Icone logout"></a></h2>
        <p>Nom: <?= $nomHTML ?></p>
        <p>Prénom: <?= $prenomHTML ?></p>
        <p>Adresse-mail: <?= $adresseMail ?></p>
    </div>
    <div class="history">
        <h2>Historique des recherches:</h2>
        <div class="history-list">
            <?php
            foreach ($historique as $item){
                ?>
                <span><img src="../ressources/img/icone.svg" alt="Icone Waps"><p><?= $item[0] ?> → <?= $item[1] ?>: <?= $item[2] ?>km</p></span>
                <?php
            }
            ?>
        </div>
    </div>
</div>