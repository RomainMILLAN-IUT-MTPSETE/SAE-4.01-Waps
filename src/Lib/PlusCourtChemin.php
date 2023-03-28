<?php

namespace App\PlusCourtChemin\Lib;

use App\PlusCourtChemin\Modele\DataObject\NoeudRoutier;
use App\PlusCourtChemin\Modele\Repository\NoeudRoutierRepository;
use SplPriorityQueue;

class PlusCourtChemin
{
    private array $distances;
    private array $noeudsALaFrontiere;

    public function __construct(
        private int $noeudRoutierDepartGid,
        private int $noeudRoutierArriveeGid
    ) {
    }

    public function calculer(bool $affichageDebug = false): ?float
    {
        $noeudRoutierRepository = new NoeudRoutierRepository();

        // Distance en km, table indexée par NoeudRoutier::gid
        $this->distances = [$this->noeudRoutierDepartGid => 0];

        $frontiere = new SplPriorityQueue();
        $frontiere->insert($this->noeudRoutierDepartGid, 0);

        while (!$frontiere->isEmpty()) {
            // Récupère le noeud avec la plus petite distance
            $noeudRoutierGidCourant = $frontiere->extract();
            // Fini
            if ($noeudRoutierGidCourant === $this->noeudRoutierArriveeGid) {
                return $this->distances[$noeudRoutierGidCourant];
            }

            /** @var NoeudRoutier $noeudRoutierCourant */
            $noeudRoutierCourant = $noeudRoutierRepository->recupererParClePrimaire($noeudRoutierGidCourant);
            $voisins = $noeudRoutierCourant->getVoisins();

            foreach ($voisins as $voisin) {
                $noeudVoisinGid = $voisin["noeud_routier_gid"];
                $distanceTroncon = $voisin["longueur"];
                $distanceProposee = $this->distances[$noeudRoutierGidCourant] + $distanceTroncon;

                if (!isset($this->distances[$noeudVoisinGid]) || $distanceProposee < $this->distances[$noeudVoisinGid]) {
                    $this->distances[$noeudVoisinGid] = $distanceProposee;
                    $priorite = $distanceProposee;
                    $frontiere->insert($noeudVoisinGid, $priorite);
                }
            }
        }
        return null;
    }

    public function calculerBidirectionnel(bool $affichageDebug = false): float
    {
        $noeudRoutierRepository = new NoeudRoutierRepository();

        $distancesDepart = [$this->noeudRoutierDepartGid => 0];
        $distancesArrivee = [$this->noeudRoutierArriveeGid => 0];

        $frontiereDepart = new SplPriorityQueue();
        $frontiereArrivee = new SplPriorityQueue();

        $frontiereDepart->insert($this->noeudRoutierDepartGid, 0);
        $frontiereArrivee->insert($this->noeudRoutierArriveeGid, 0);

        $noeudCommunGid = null;
        $distanceMinimale = INF;

        while (!$frontiereDepart->isEmpty() && !$frontiereArrivee->isEmpty()) {
            // Direction depart -> arrivee
            $noeudRoutierGidDepartCourant = $frontiereDepart->extract();

            // ** @var NoeudRoutier $noeudRoutierDepartCourant
            $noeudRoutierDepartCourant = $noeudRoutierRepository->recupererParClePrimaire($noeudRoutierGidDepartCourant);
            $voisinsDepart = $noeudRoutierDepartCourant->getVoisins();

            foreach ($voisinsDepart as $voisin) {
                $noeudVoisinGid = $voisin["noeud_routier_gid"];
                $distanceTroncon = $voisin["longueur"];
                $distanceProposee = $distancesDepart[$noeudRoutierGidDepartCourant] + $distanceTroncon;

                if (!isset($distancesDepart[$noeudVoisinGid]) || $distanceProposee < $distancesDepart[$noeudVoisinGid]) {
                    $distancesDepart[$noeudVoisinGid] = $distanceProposee;
                    $priorite = $distanceProposee;
                    $frontiereDepart->insert($noeudVoisinGid, $priorite);

                    // Vérifie si le noeud est également dans la frontière d'arrivée
                    if (isset($distancesArrivee[$noeudVoisinGid])) {
                        $distance = $distancesDepart[$noeudVoisinGid] + $distancesArrivee[$noeudVoisinGid];

                        if ($distance < $distanceMinimale) {
                            $noeudCommunGid = $noeudVoisinGid;
                            $distanceMinimale = $distance;
                        }
                    }
                }
            }

            // Direction arrivee -> depart
            $noeudRoutierGidArriveeCourant = $frontiereArrivee->extract();

            // @var NoeudRoutier $noeudRoutierArriveeCourant
            $noeudRoutierArriveeCourant = $noeudRoutierRepository->recupererParClePrimaire($noeudRoutierGidArriveeCourant);
            //TODO a terminer bidirectionnel
        }
    }



    private function noeudALaFrontiereDeDistanceMinimale()
    {
        $noeudRoutierDistanceMinimaleGid = -1;
        $distanceMinimale = PHP_INT_MAX;
        foreach ($this->noeudsALaFrontiere as $noeudRoutierGid => $valeur) {
            if ($this->distances[$noeudRoutierGid] < $distanceMinimale) {
                $noeudRoutierDistanceMinimaleGid = $noeudRoutierGid;
                $distanceMinimale = $this->distances[$noeudRoutierGid];
            }
        }
        return $noeudRoutierDistanceMinimaleGid;
    }
}
