<?php

namespace App\PlusCourtChemin\Controleur;

use App\PlusCourtChemin\Lib\MessageFlash;

class ControleurHome extends ControleurGenerique
{
    public static function afficherErreur($errorMessage = "", $controleur = ""): void
    {
        parent::afficherErreur($errorMessage, "noeudCommune");
    }

    public static function accueil(): void
    {
        ControleurHome::afficherVue('vueGenerale.php', [
            "pagetitle" => "Accueil",
            "cheminVueBody" => "home/home.php"
        ]);
    }
}