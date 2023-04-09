<?php

namespace App\PlusCourtChemin\Controleur;

use App\PlusCourtChemin\Lib\ConnexionUtilisateur;
use App\PlusCourtChemin\Lib\Historique;
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

    public static function pcc(): void {
        ControleurNoeudCommune::afficherVue('vueGenerale.php', [
            "pagetitle" => "Détail de la Commune",
            "cheminVueBody" => "noeudCommune/plusCourtChemin.php"
        ]);
    }

    public static function plusCourtChemin(): void
    {
        if (!empty($_POST)) {
            $nomCommuneDepart = $_POST["nomCommuneDepart"];
            $nomCommuneArrivee = $_POST["nomCommuneArrivee"];

            $noeudCommuneRepository = new NoeudCommuneRepository();
            /** @var NoeudCommune $noeudCommuneDepart */
            $noeudCommuneDepartRepo = $noeudCommuneRepository->recupererPar(["nom_comm" => $nomCommuneDepart]);
            if($noeudCommuneDepartRepo == null){
                MessageFlash::ajouter("danger", "Commune ".$nomCommuneDepart." inconnue");
                ControleurNoeudCommune::rediriger("noeudCommune", "plusCourtChemin");
            }
            $noeudCommuneDepart = $noeudCommuneDepartRepo[0];
            /** @var NoeudCommune $noeudCommuneArrivee */
            $noeudCommuneArriveeRepo = $noeudCommuneRepository->recupererPar(["nom_comm" => $nomCommuneArrivee]);
            if($noeudCommuneArriveeRepo == null){
                MessageFlash::ajouter("danger", "Commune ".$nomCommuneArrivee." inconnue");
                ControleurNoeudCommune::rediriger("noeudCommune", "plusCourtChemin");
            }
            $noeudCommuneArrivee = $noeudCommuneArriveeRepo[0];

            $noeudRoutierRepository = new NoeudRoutierRepository();
            $noeudRoutierDepartGid = $noeudRoutierRepository->recupererPar([
                "id_rte500" => $noeudCommuneDepart->getId_nd_rte()
            ])[0];
            $noeudRoutierArriveeGid = $noeudRoutierRepository->recupererPar([
                "id_rte500" => $noeudCommuneArrivee->getId_nd_rte()
            ])[0];

            $pcc = new PlusCourtChemin($noeudRoutierDepartGid, $noeudRoutierArriveeGid, $noeudRoutierRepository);
            $calculer = $pcc->calculer();

            if(ConnexionUtilisateur::estConnecte()){
                Historique::ajouter($nomCommuneDepart, $nomCommuneArrivee, $calculer["distances"]);
            }

            $parametres["nomCommuneDepart"] = $nomCommuneDepart;
            $parametres["nomCommuneArrivee"] = $nomCommuneArrivee;
            $parametres["distance"] = $calculer["distances"];

            echo(json_encode($parametres));
        }
    }

    public static function getLatitudeLongitude(){
        if (!empty($_POST)){
            $noeudCommuneDepart = (new NoeudCommuneRepository)->recupererPar(["nom_comm" => $_POST["nomCommuneDepart"]])[0];
            $noeudCommuneArrivee = (new NoeudCommuneRepository)->recupererPar(["nom_comm" => $_POST["nomCommuneArrivee"]])[0];
            $noeudRoutierDepartGid = (new NoeudRoutierRepository)->recupererPar([
                "id_rte500" => $noeudCommuneDepart->getId_nd_rte()
            ])[0];
            $noeudRoutierArriveeGid = (new NoeudRoutierRepository)->recupererPar([
                "id_rte500" => $noeudCommuneArrivee->getId_nd_rte()
            ])[0];
            echo(json_encode(new NoeudRoutierRepository)->getLatitudeLongitude($noeudRoutierDepartGid))
        }
    }

    public static function getNomsCommunesJSON(): void{
        echo(json_encode((new NoeudCommuneRepository)->getNomsCommunesJSON()));
    }
}