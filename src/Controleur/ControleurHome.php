<?php

namespace App\PlusCourtChemin\Controleur;

use App\PlusCourtChemin\Lib\MessageFlash;

class ControleurHome
{
    private static function afficheVue(string $cheminVue, array $parametres = []): void
    {
        extract($parametres);
        require_once DIR . "/../vue/$cheminVue";
    }

    public static function accueil(): void
    {
        self::afficheVue('home/home.php', []);
    }

    public static function home(): void
    {
        self::afficheVue('vueGenerale.php', ['pagetitle' => 'Waps', 'cheminVueBody' => 'home/home.php']);
    }

    public static function afficherErreur(): void
    {
        MessageFlash::ajouter('warning', "Erreur contrôleur Home !");
        header("Location: index.php");
        exit();
    }
    public static function self() {
        self::afficheVue('home/home.php', ['pagetitle' => 'Waps']);
    }
}