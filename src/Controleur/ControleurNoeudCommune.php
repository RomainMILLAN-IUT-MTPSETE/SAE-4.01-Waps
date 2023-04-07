<?php

namespace App\PlusCourtChemin\Controleur;

use App\PlusCourtChemin\Lib\MessageFlash;
use App\PlusCourtChemin\Lib\PlusCourtChemin;
use App\PlusCourtChemin\Modele\DataObject\NoeudCommune;
use App\PlusCourtChemin\Modele\HTTP\Cookie;
use App\PlusCourtChemin\Modele\Repository\NoeudCommuneRepository;
use App\PlusCourtChemin\Modele\Repository\NoeudRoutierRepository;

class ControleurNoeudCommune extends ControleurGenerique
{

    public static function afficherErreur($errorMessage = "", $controleur = ""): void
    {
        parent::afficherErreur($errorMessage, "noeudCommune");
    }

    public static function afficherListe(): void
    {
        $noeudsCommunes = (new NoeudCommuneRepository())->getAllNoeudCommuneOrderByName();     //appel au modèle pour gerer la BD
        ControleurNoeudCommune::afficherVue('vueGenerale.php', [
            "noeudsCommunes" => $noeudsCommunes,
            "pagetitle" => "Liste des Communes",
            "cheminVueBody" => "noeudCommune/liste.php"
        ]);
    }

    public static function rechercher(): void
    {
        if(!isset($_REQUEST['search'])){
            MessageFlash::ajouter("danger", "Aucune recherche manquante.");
            ControleurNoeudCommune::rediriger("noeudCommune", "plusCourtChemin");
        }

        $nom_comm = $_REQUEST['search'];
        $noeudCommune = (new NoeudCommuneRepository())->getNoeudCommuneByName($nom_comm);

        ControleurNoeudCommune::rediriger("noeudCommune", "afficherDetail", ['gid' => $noeudCommune->getGid()]);
    }

    public static function afficherDetail(): void
    {
        if (!isset($_REQUEST['gid'])) {
            MessageFlash::ajouter("danger", "Identifiant manquante.");
            ControleurNoeudCommune::rediriger("noeudCommune", "afficherListe");
        }

        $gid = $_REQUEST['gid'];
        $noeudCommune = (new NoeudCommuneRepository())->getNoeudCommune($gid);

        if ($noeudCommune === null) {
            MessageFlash::ajouter("warning", "Commune inconnue.");
            ControleurNoeudCommune::rediriger("noeudCommune", "afficherListe");
        }

        ControleurNoeudCommune::afficherVue('vueGenerale.php', [
            "noeudCommune" => $noeudCommune,
            "pagetitle" => "Détail de la Commune",
            "cheminVueBody" => "noeudCommune/detail.php"
        ]);
    }

    public static function plusCourtChemin(): void
    {
        $parametres = [
            "pagetitle" => "Plus court chemin",
            "cheminVueBody" => "noeudCommune/plusCourtChemin.php",
        ];


        if (!empty($_POST)) {
            $nomCommuneDepart = $_POST["nomCommuneDepart"];
            $nomCommuneArrivee = $_POST["nomCommuneArrivee"];

            $noeudCommuneRepository = new NoeudCommuneRepository();
            /** @var NoeudCommune $noeudCommuneDepart */
            $noeudCommuneDepart = $noeudCommuneRepository->recupererPar(["nom_comm" => $nomCommuneDepart])[0];
            /** @var NoeudCommune $noeudCommuneArrivee */
            $noeudCommuneArrivee = $noeudCommuneRepository->recupererPar(["nom_comm" => $nomCommuneArrivee])[0];

            $noeudRoutierRepository = new NoeudRoutierRepository();
            $noeudRoutierDepartGid = $noeudRoutierRepository->recupererPar([
                "id_rte500" => $noeudCommuneDepart->getId_nd_rte()
            ])[0];
            $noeudRoutierArriveeGid = $noeudRoutierRepository->recupererPar([
                "id_rte500" => $noeudCommuneArrivee->getId_nd_rte()
            ])[0];

            $pcc = new PlusCourtChemin($noeudRoutierDepartGid, $noeudRoutierArriveeGid, $noeudRoutierRepository);
            $distance = $pcc->calculer();

            $parametres["nomCommuneDepart"] = $nomCommuneDepart;
            $parametres["nomCommuneArrivee"] = $nomCommuneArrivee;
            $parametres["distance"] = $distance;
        }

        ControleurNoeudCommune::afficherVue('vueGenerale.php', $parametres);
    }

    public static function getNomsCommunesJSON(): void{
        echo(json_encode((new NoeudCommuneRepository)->getNomsCommunesJSON()));
    }
}