<?php

namespace App\PlusCourtChemin\Lib;

use App\PlusCourtChemin\Modele\HTTP\Session;

class Historique
{
    private static string $cleHisotrique = "_history";

    public static function ajouter(string $depart, string $arrive, string $distance): void
    {
        $session = Session::getInstance();

        $history = [];
        if ($session->existeCle(Historique::$cleHisotrique))
            $history = $session->lire(Historique::$cleHisotrique);

        $history[] = [$depart, $arrive, $distance];
        $session->enregistrer(Historique::$cleHisotrique, $history);
    }

    public static function contientMessage(): bool
    {
        $session = Session::getInstance();
        return $session->existeCle(Historique::$cleHisotrique) && !empty($session->lire(Historique::$cleHisotrique));
    }

    public static function lireHistorique(): array
    {
        $session = Session::getInstance();
        if (!Historique::contientMessage())
            return [];

        return $session->lire(Historique::$cleHisotrique);
    }
}